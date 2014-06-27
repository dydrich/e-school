<?php

require_once 'ClassbookData.php';
require_once 'RBTime.php';
require_once 'ClassbookPDF.php';

class Classbook {
	
	private $classData;
	private $days;
	private $datasource;
	private $year;
	private $cls;
	private $studentsData;
	private $pdf;
	private $path;
	private $schedule;
	private $cdc;
	/**
	 * 
	 * @var integer
	 * max number of hours in a day
	 */
	private $hours;
	
	public function __construct(Classe $c, SchoolYear $y, $ld, $ds, $path){
		$this->year = $y;
		$this->cls = $c;
		$this->datasource = new MySQLDataLoader($ds);
		$this->classData = new ClassbookData($c, $y, $ld, $ds);
		$this->loadDays();
		$this->loadStudentsData();
		$this->loadAddresses();
		$this->loadSchedule();
		$this->loadCDC();
		$this->pdf = new ClassbookPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->path = $path;
		$this->pdf->setFilePath($path);
	}
	
	private function getDay($d){
		return $this->days[$d];
	}
	
	private function loadDays(){
		$days = $this->datasource->executeQuery("SELECT id_reg, data, ingresso, uscita FROM rb_reg_classi WHERE id_anno = {$this->year->getYear()->get_ID()} AND id_classe = {$this->cls->get_ID()} ORDER BY data");
		foreach ($days as $day){
			$this->days[$day['data']] = array("id" => $day['id_reg'], "data" => $day['data'], "ingresso" => $day['ingresso'], "uscita" => $day['uscita'], "assenti" => array(), "ritardi" => array(), "anticipi" => array(), "giustificazioni" => array());
			/*
			 * firme
			 */
			$sel_signatures = "SELECT ora, docente, cognome, nome, rb_materie.materia, argomento FROM rb_reg_firme, rb_utenti, rb_materie WHERE id_registro = {$day['id_reg']} AND docente = uid AND rb_reg_firme.materia = id_materia ORDER BY ora";
			$signatures = $this->datasource->executeQuery($sel_signatures);
			$this->days[$day['data']]['firme'] = array();
			if ($signatures){
				foreach ($signatures as $sig){
					$this->days[$day['data']]['firme'][$sig['ora']] = $sig;
				}
			}
			/*
			 * note disciplinari
			 */
			$sel_note = "SELECT docente, CONCAT_WS(' ', rb_utenti.cognome, rb_utenti.nome) AS doc, classe, alunno, descrizione FROM rb_note_disciplinari, rb_utenti WHERE docente = uid AND classe = {$this->cls->get_ID()} AND anno = {$this->year->getYear()->get_ID()} AND data = '{$day['data']}'";
			$note = $this->datasource->executeQuery($sel_note);
			if ($note){
				foreach ($note as $nota){
					if (!isset($this->days[$day['data']]['note'])){
						$this->days[$day['data']]['note'] = array();
					}
					if ($nota['alunno'] != ""){
						//var_dump($nota);
						$nota['desc_alunno'] = $this->datasource->executeCount("SELECT CONCAT_WS(cognome, nome) FROM rb_alunni WHERE id_alunno = {$nota['alunno']}");
					}
					$this->days[$day['data']]['note'][] = $nota;
				}
			}
		}
	}
	
	private function loadStudentsData(){
		$sel_dati_alunni .= "SELECT rb_reg_alunni.ingresso, rb_reg_alunni.uscita, data, giustificata, rb_reg_alunni.id_alunno, cognome, nome, data_nascita FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE rb_reg_alunni.id_alunno = rb_alunni.id_alunno AND rb_reg_classi.id_classe = {$this->cls->get_ID()} AND rb_reg_classi.id_reg = rb_reg_alunni.id_registro AND id_anno = {$this->year->getYear()->get_ID()} AND id_reg = id_registro ORDER BY cognome, nome, data";
		$res_dati_alunni = $this->datasource->executeQuery($sel_dati_alunni);
		$studentsData = array();
		$dt = "";
		foreach ($res_dati_alunni as $row){
			if ($dt != $row['data']) {
				$dt = $row['data'];
				$dayData = $this->getDay($row['data']);
				list($h, $m, $s) = explode(":", $dayData['ingresso']);
				$ing = new RBTime($h, $m, $s);
				list($h, $m, $s) = explode(":", $dayData['uscita']);
				$usc = new RBTime($h, $m, $s);
			}
			if (!$studentsData[$row['id_alunno']]){
				$studentsData[$row['id_alunno']] = array("cognome" => $row['cognome'], "nome" => $row['nome'], "data_nascita" => $row['data_nascita'], "assenze" => array(), "ritardi" => array(), "anticipi" => array(), "indirizzi" => array("indirizzo" => "", "t1" => "", "t2" => "", "t3" => ""));
			}
			if ($row['ingresso'] == "") {
				/*
				 * alunno assente
				 */
				$this->days[$row['data']]['assenti'][] = $row['cognome']." ".$row['nome'];
				$studentsData[$row['id_alunno']]['assenze'][] = $row['data'];
				/*
				 * giustificazione assenza
				 */
				$this->days[$row['giustificata']]['giustificazioni'][] = array("stud" => $row['cognome']." ".$row['nome'], "data" => $row['data']);
			}
			else {
				/*
				 * alunno presente: valutazione di ritardi e uscite anticipate
				 */
				list($h, $m, $s) = explode(":", $row['ingresso']);
				$ing_al = new RBTime($h, $m, $s);
				list($h, $m, $s) = explode(":", $row['uscita']);
				$usc_al = new RBTime($h, $m, $s);
				/*
				 * ritardi
				 */
				if ($ing_al->getTime() > $ing->getTime()){
					$this->days[$row['data']]['ritardi'][] = array("studente" => $row['cognome']." ".$row['nome'], "ingresso" => $row['ingresso']);
					$studentsData[$row['id_alunno']]['ritardi'][] = array("data" => $row['data'], "ingresso" => $row['ingresso']);
				}
				/*
				 * uscite anticipate
				 */
				if ($usc->getTime() > $usc_al->getTime()){
					$this->days[$row['data']]['anticipi'][] = array("studente" => $row['cognome']." ".$row['nome'], "uscita" => $row['uscita']);
					$studentsData[$row['id_alunno']]['anticipi'][] = array("data" => $row['data'], "uscita" => $row['uscita']);
				}
			}
		}
		$this->studentsData = $studentsData;
	}
	
	private function loadAddresses(){
		$sel = "SELECT rb_indirizzi_alunni.* FROM rb_indirizzi_alunni, rb_alunni WHERE rb_indirizzi_alunni.id_alunno = rb_alunni.id_alunno AND id_classe = {$this->cls->get_ID()}";
		$addresses = $this->datasource->executeQuery($sel);
		if ($addresses != false){
			foreach ($addresses as $row){
				$this->studentsData[$row['id_alunno']]['indirizzi'] = array("indirizzo" => $row['indirizzo'], "t1" => $row['telefono1'], "t2" => $row['telefono2'], "t3" => $row['telefono3']);
			}
		}
	}
	
	private function loadSchedule(){
		$sel_orario = "SELECT id, giorno, ora, rb_materie.materia FROM rb_orario, rb_materie WHERE rb_orario.materia = id_materia AND classe = {$this->cls->get_ID()} AND anno = {$this->year->getYear()->get_ID()} ORDER BY giorno, ora";
		$res_orario = $this->datasource->execute($sel_orario);
		$orario = array();
		$this->hours = 0;
		foreach ($res_orario as $ora){
			if ($ora['ora'] > $this->hours){
				$this->hours = $ora['ora'];
			}
			if (!isset($orario[$ora['giorno']])){
				$orario[$ora['giorno']] = array();
			}
			$orario[$ora['giorno']][$ora['ora']] = array("id" => $ora['id'], "giorno" => $ora['giorno'], "ora" => $ora['ora'], "materia" => $ora['materia']);
		}
		$this->schedule = $orario;
	}
	
	private function loadCDC(){
		$query = "SELECT cognome, nome, materia FROM rb_utenti, rb_materie, rb_cdc WHERE rb_cdc.id_docente = rb_utenti.uid AND rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_anno = {$this->year->getYear()->get_ID()} AND rb_cdc.id_classe = {$this->cls->get_ID()} ORDER BY materia, cognome, nome ";
		$result = $this->datasource->execute($query);
		$this->cdc = $result;
	}
	
	public function createPDF(){
		$this->pdf->init($this->classData, $this->studentsData, $this->cls, $this->year, $this->days, $this->cdc, $this->schedule, $this->hours);
		$this->pdf->createClassbook();
	}
	
	public function getPDF(){
	
	}
	
}