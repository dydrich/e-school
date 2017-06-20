<?php

require_once "classes.php";
require_once "data_source.php";
require_once "Student.php";
require_once "SchoolYear.php";

class StudentManager
{
	private $datasource 	= null;
	private $student;
	private $existClassbook;
	private $existReport;
	private $schoolYear;
	
	function __construct($ds, Student $s){
		$this->datasource = new MySQLDataLoader($ds);
		$this->student = $s;
		$this->existClassbook = $this->checkClassbook();
		$this->existReport = $this->checkReports();
	}
	
	public function setSchoolYear(SchoolYear $s){
		$this->schoolYear = $s;
	}
	
	public function getSchoolYear(){
		return $this->schoolYear;
	}
	
	public function addStudent(){
		/* get the next available ID */
		$nextID = $this->datasource->executeCount("SELECT auto_increment FROM information_schema.tables WHERE table_name='rb_alunni'");
		$this->student->setUid($nextID);
		$this->insertStudent();
		if($this->existClassbook){
			$this->insertClassbook();
		}
		if($this->existReport){
			$this->insertReport();
		}
	}
	
	public function deleteStudent($reason = "TRASFERITO"){
		require_once 'EventLogFactory.php';
		require_once "EventLogDB.php";
		$id_classe = $this->datasource->executeCount("SELECT id_classe FROM rb_alunni WHERE id_alunno = ".$this->student->getUid());
		$data = array('classe' => $id_classe, 'std' => $this->student->getUid());
		$elf = \eschool\EventLogFactory::getInstance($data, $this->datasource);
		$logger = $elf->getEventLog();
		$logger->logStudentDeleted();
		$this->datasource->executeUpdate("UPDATE rb_alunni SET attivo = '0', password = '{$reason}', id_classe = NULL WHERE id_alunno = {$this->student->getUid()}");
	}
	
	public function updateAccount(){
		$uname = $this->getStudent()->getUsername();
		$pwd = $this->getStudent()->getPwd();
		$alunno = $this->student->getUid();
		$update = "UPDATE rb_alunni SET username = '{$uname}'";
		if(trim($pwd) != ""){
			$update .= ", password = '{$pwd}' ";
		}
		$update .= " WHERE id_alunno = {$alunno}";
		$this->datasource->executeUpdate($update);
	}
	
	public function updateProfile(){
		
	}
	
	public function insertStudent(){
		$fname = $this->student->getFirstName();
		$lname = $this->student->getLastName();
		$sex = $this->student->getSex();
		$birthday = $this->student->getBirthday();
		$cf = $this->student->getCf();
		$uname = $this->getStudent()->getUsername();
		$pwd = $this->getStudent()->getPwd();
		$id_classe = $this->student->getClass();
		$luogo_nascita = $this->student->getBirthPlace();
		$uid = $this->datasource->executeUpdate("INSERT INTO rb_alunni (username, password, nome, cognome, data_nascita, luogo_nascita, codice_fiscale, id_classe, sesso) VALUES ('{$uname}', '{$pwd}', '{$fname}', '{$lname}', ".field_null($birthday, true).", ".field_null($luogo_nascita, true).", ".field_null($cf, true).", {$id_classe}, '{$sex}')");
		if (is_installed("com")) {
			$uniqID = $this->datasource->executeUpdate("INSERT INTO rb_com_users (uid, table_name, type) VALUES ({$uid}, 'rb_alunni', 'student')");
			$this->student->setUniqID($uniqID);
		}
		$this->student->setUid($uid);
	}
	
	public function updateStudent(){
		$fname = $this->student->getFirstName();
		$lname = $this->student->getLastName();
		$sex = $this->student->getSex();
		$birthday = $this->student->getBirthday();
		$cf = $this->student->getCf();
		$alunno = $this->student->getUid();
		$luogo_nascita = $this->student->getBirthPlace();
		$this->datasource->executeUpdate("UPDATE rb_alunni SET nome = '{$fname}', cognome = '{$lname}', data_nascita = ".field_null($birthday, true).", codice_fiscale = ".field_null($cf, true).", luogo_nascita = ".field_null($luogo_nascita, true)." WHERE id_alunno = {$alunno}");
	}
	
	public function setStudent(StudentBean $s){
		$this->student = $s;
	}
	
	public function getStudent(){
		return $this->student;
	}

	private function checkClassbook() {
		$id_anno = $_SESSION['__current_year__']->get_ID();
		$alunno = $this->student->getUid();
		$sel_reg = "SELECT COUNT(rb_reg_alunni.id_registro) FROM rb_reg_alunni, rb_reg_classi WHERE id_anno = {$id_anno} AND id_registro = id_reg AND id_alunno = {$alunno}";
		return $this->datasource->executeCount($sel_reg);
	}

	private function checkReports() {
		$id_anno = $_SESSION['__current_year__']->get_ID();
		$alunno = $this->student->getUid();
		$check_reports = "SELECT COUNT(*) FROM rb_scrutini WHERE anno = {$id_anno} AND quadrimestre = 1 AND alunno = {$alunno}";
		return $this->datasource->executeCount($check_reports);
	}

	public function changeClass(){
		$alunno = $this->student->getUid();
		$new_class = $this->student->getClass();
		$this->datasource->executeUpdate("UPDATE rb_alunni SET id_classe = {$new_class} WHERE id_alunno = {$alunno}");
		if($this->existClassbook){
			$this->updateClassbook();
		}
		if($this->existReport){
			$this->updateReport();
		}
	}
	
	public function insertClassbook(){
		/* classbook data */
		$orari = [];
		$id_anno = $this->schoolYear->getYear()->get_ID();
		$id_classe = $this->student->getClass();
		$alunno = $this->student->getUid();
		$sel_registro = "SELECT id_reg, ingresso, uscita, data FROM rb_reg_classi WHERE id_classe = {$id_classe} AND id_anno = {$id_anno} ORDER BY id_reg ";
		$res_registro = $this->datasource->executeQuery($sel_registro);

		foreach($res_registro as $day){
			$orari[$day['data']] = [];
			$orari[$day['data']]['new_id_registro'] = $day['id_reg'];
			$orari[$day['data']]['new_enter'] = $day['ingresso'];
			$orari[$day['data']]['new_exit'] = $day['uscita'];
			$insert_al = "INSERT IGNORE INTO rb_reg_alunni VALUES ({$day['id_reg']}, {$alunno}, '{$day['ingresso']}', '{$day['uscita']}', NULL, NULL, {$id_classe})";
			$this->datasource->executeUpdate($insert_al);
		}
		return $orari;
	}
	
	public function insertReport(){
		/* assessment data */
		$id_anno = $this->schoolYear->getYear()->get_ID();
		$id_classe = $this->student->getClass();
		$fq = format_date($this->schoolYear->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$quadrimestre = date("Y-m-d") > $fq ? 2 : 1;
		$alunno = $this->student->getUid();
		$subjects = $this->getSubjects($id_anno, $quadrimestre, $id_classe);
		if ($subjects && count($subjects) > 0) {
			foreach($subjects as $subject){
				$this->datasource->executeUpdate("INSERT INTO rb_scrutini (alunno, classe, anno, quadrimestre, materia) VALUES ({$alunno}, {$id_classe}, {$id_anno}, 1, {$subject})");
				$this->datasource->executeUpdate("INSERT INTO rb_scrutini (alunno, classe, anno, quadrimestre, materia) VALUES ({$alunno}, {$id_classe}, {$id_anno}, 2, {$subject})");
			}
		}
		/* report data */
		$desc_class = $this->datasource->executeCount("SELECT CONCAT(anno_corso, sezione) FROM rb_classi WHERE id_classe = {$id_classe}");
		$pub = [];
		$pub[] = $this->datasource->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$id_anno} AND quadrimestre = 1");
		$pub[] = $this->datasource->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$id_anno} AND quadrimestre = 2");
		foreach ($pub as $d){
			$this->datasource->executeUpdate("REPLACE INTO rb_pagelle (id_pubblicazione, id_alunno, id_classe, desc_classe) VALUES ({$d}, {$alunno}, {$id_classe}, '{$desc_class}')");
		}
	}
	
	public function updateClassbook(){
		$orari = $this->insertClassbook();
		$id_anno = $this->schoolYear->getYear()->get_ID();
		$old_class = $this->student->getOldClass();
		$alunno = $this->student->getUid();
		$res_old_registro = $this->datasource->executeQuery("SELECT id_reg, ingresso, uscita, data FROM rb_reg_classi WHERE id_classe = {$old_class} AND id_anno = {$id_anno} ORDER BY id_reg ");
		foreach($res_old_registro as $old_day){
			$orari[$old_day['data']]['old_id_registro'] = $old_day['id_reg'];
			$orari[$old_day['data']]['old_enter'] = $old_day['ingresso'];
			$orari[$old_day['data']]['old_exit'] = $old_day['uscita'];
		}
		
		// assenze
		$res_abs = $this->datasource->executeQuery("SELECT id_reg, data, giustificata FROM rb_reg_classi, rb_reg_alunni WHERE id_reg = id_registro AND rb_reg_classi.id_classe = {$old_class} AND id_anno = {$id_anno} AND id_alunno = {$alunno} AND rb_reg_alunni.ingresso IS NULL");
		if($res_abs){
			foreach($res_abs as $dt){
				$this->datasource->executeUpdate("UPDATE rb_reg_alunni SET ingresso = NULL, uscita = NULL, giustificata = '{$dt['giustificata']}' WHERE id_registro = ".$orari[$dt['data']]['new_id_registro']." AND id_alunno = {$alunno}");
			}
		}
		foreach($orari as $d_day){
			if (isset($d_day['old_id_registro'])) {
				$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni WHERE id_registro = " . $d_day['old_id_registro'] . " AND id_alunno = {$alunno}");
			}
		}
	}
	
	public function updateReport(){
		/* assessment data */
		$id_anno = $this->schoolYear->getYear()->get_ID();
		$id_classe = $this->student->getClass();
		$fq = format_date($this->schoolYear->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "");
		$quadrimestre = date("Y-m-d") > $fq ? 2 : 1;
		$alunno = $this->student->getUid();
		if($quadrimestre == 1){
			$this->datasource->executeUpdate("UPDATE rb_scrutini SET classe = {$id_classe} WHERE alunno = {$alunno} AND anno = {$id_anno}");
		}
		else{
			$this->datasource->executeUpdate("UPDATE rb_scrutini SET classe = {$id_classe} WHERE alunno = {$alunno} AND anno = {$id_anno} AND quadrimestre = 2");
		}

		/* report data */
		$desc_classe = $this->datasource->executeCount("SELECT CONCAT(anno_corso, sezione) FROM rb_classi WHERE id_classe = {$id_classe}");
		if($quadrimestre == 1){
			$pub = $this->datasource->executeQuery("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$id_anno}");
		}
		else{
			$pub = $this->datasource->executeQuery("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$id_anno} AND quadrimestre = 2");
		}
		foreach ($pub as $d){
			$this->datasource->executeUpdate("UPDATE rb_pagelle SET id_classe = {$id_classe}, desc_classe = '{$desc_classe}' WHERE id_alunno = {$alunno} AND id_pubblicazione = $d");
		}
	}
	
	public function getSubjects($id_anno, $quadrimestre, $id_classe){
		$sel_subjects = "SELECT DISTINCT materia FROM rb_scrutini WHERE anno = {$id_anno} AND quadrimestre = {$quadrimestre} AND classe = {$id_classe}";
		$subjects = $this->datasource->executeQuery($sel_subjects);
		return $subjects;
	}
	
	public function setExistClassbook($bool){
		$this->existClassbook = $bool;
	}
	
	public function getExistClassbook(){
		return $this->existClassbook;
	}
	
	public function setExistReport($bool){
		$this->existReport = $bool;
	}
	
	public function getExistReport(){
		return $this->existReport;
	}
}
