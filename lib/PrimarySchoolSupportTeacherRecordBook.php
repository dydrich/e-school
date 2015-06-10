<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 02/04/14
 * Time: 20.09
 */

class PrimarySchoolSupportTeacherRecordBook extends PrimarySchoolTeacherRecordBook{

	private $studentData;
	private $activities;

	public function __construct(SchoolUserBean $teacher, MySQLDataLoader $ds, $pt, AnnoScolastico $y, $p, SchoolYear $sy){
		$this->teacher = $teacher;
		$this->datasource = $ds;
		$this->path = $pt;
		$this->year = $y;
		$this->pubbID = $p;
		$this->schoolYear = $sy;
		$this->attachments = array();
		$this->setRecordBooks();
		$this->pdf = new PrimarySchoolSupportTeacherRecordBookPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->setPath($this->path);
		$this->pdf->setDatasource($ds);
		$this->pdf->setYear($y);
	}

	public function setCls($cls){
		$this->cls = $cls;
	}

	public function loadAttachments($cls, $student){
		$id = $this->existsRecordBook($cls, $student);
		if ($id){
			$att = $this->datasource->executeQuery("SELECT * FROM rb_allegati_registro_docente WHERE registro = {$id}");
			if (count($att) > 0){
				foreach ($att as $a){
					$this->attachments[] = $a;
				}
			}
		}
	}

	protected function setRecordBooks(){

	}

	public function getRecordBooks(){

	}

	public function loadStudentData($cls, $st){
		$_st = $this->datasource->executeQuery("SELECT rb_alunni.id_alunno AS id_alunno, cognome, nome, data_nascita, luogo_nascita, rb_esiti.esito, positivo FROM rb_alunni, rb_pagelle LEFT JOIN rb_esiti ON rb_pagelle.esito = id_esito WHERE id_pubblicazione = {$this->pubbID} AND rb_alunni.id_classe = {$cls} AND rb_alunni.id_alunno = rb_pagelle.id_alunno AND attivo = '1' AND rb_alunni.id_alunno = {$st}");
		$student = array();
		$student['id'] = $_st[0]['id_alunno'];
		$student['name'] = $_st[0]['cognome']." ".$_st[0]['nome'];
		$student['cognome'] = $_st[0]['cognome'];
		$student['nome'] = $_st[0]['nome'];
		$student['esito'] = $_st[0]['esito'];
		$student['positivo'] = $_st[0]['positivo'];
		$student['data_nascita'] = $_st[0]['data_nascita'];
		$student['luogo_nascita'] = $_st[0]['luogo_nascita'];

		$student['indirizzo'] = "";
		$student['citta'] = "";
		$student['telefono'] = "";
		$_address = $this->datasource->executeQuery("SELECT indirizzo, citta FROM rb_indirizzi_alunni WHERE id_alunno = {$st}");
		if ($_address){
			$address = $_address[0];
			$student['indirizzo'] = $address['indirizzo'];
			$student['citta'] = $address['citta'];
		}

		$student['sostegno'] = array();
		$r = $this->datasource->executeQuery("SELECT * FROM rb_dati_sostegno WHERE alunno = {$st} AND anno_scolastico = {$this->year->get_ID()}");
		$student['sostegno'] = $r[0];

		$sel_grades = "SELECT * FROM rb_scrutini WHERE classe = {$cls} AND anno = {$this->year->get_ID()} AND alunno = {$st}";
		$rows = $this->datasource->executeQuery($sel_grades);

		foreach ($rows as $row){
			if ($row['quadrimestre'] == 1){
				$student['voti'][$row['materia']]['voto1q'] = $row['voto'];
				$student['voti'][$row['materia']]['assenze1q'] = $row['assenze'];
			}
			else if ($row['quadrimestre'] == 2){
				$student['voti'][$row['materia']]['voto2q'] = $row['voto'];
				$student['voti'][$row['materia']]['assenze2q'] = $row['assenze'];
			}
		}
		$sel_comp = "SELECT alunno, voto, quadrimestre FROM rb_scrutini WHERE classe = {$cls} AND anno = {$this->year->get_ID()} AND materia = 40 AND alunno = {$st}";
		$res_comp = $this->datasource->executeQuery($sel_comp);

		foreach ($res_comp as $res){
			if ($res['quadrimestre'] == 1){
				$student['voti']['comp1q'] = $res['voto'];
				//echo $grades[$res['alunno']]['comp1q']."<br>";
			}
			else if ($res['quadrimestre'] == 2){
				$student['voti']['comp2q'] = $res['voto'];
			}
		}
		$this->studentData = $student;
		return $student;
	}

	public function loadClassData($c){
		$cls = $this->datasource->executeQuery("SELECT id_classe, CONCAT(anno_corso, sezione) AS cls FROM rb_classi WHERE id_classe = {$c}");
		$id_cls = $cls[0]['id_classe'];
		$num_students = $this->datasource->executeCount("SELECT COUNT(*) FROM rb_alunni WHERE id_classe = {$id_cls} AND attivo = '1'");
		$my_class = $cls[0];
		$my_class['num_std'] = $num_students;
		$my_class['teachers'] = array();
		$query = "SELECT CONCAT_WS(' ', cognome, nome) AS name, materia AS subj FROM rb_utenti, rb_materie, rb_cdc WHERE rb_cdc.id_docente = rb_utenti.uid AND rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_anno = ".$_SESSION['__current_year__']->get_ID()." AND rb_cdc.id_classe = {$id_cls}";
		$result = $this->datasource->executeQuery($query);
		foreach ($result as $row){
			$my_class['teachers'][] = $row;
		}
		$this->cls = $my_class;
	}

	public function createRecordBook($cls, $st){
		$this->loadClassData($cls);
		$this->loadStudentData($cls, $st);
		@$this->loadAttachments($cls, $st);
		$this->loadActivities();
		$this->pdf->init($this->teacher, $this->cls, $this->pubbID, $this->studentData, $this->attachments);
		$this->registerRecordBook($cls, $st);
		$this->pdf->createRecordBook();
	}

	protected function loadActivities(){
		$this->activities = $this->datasource->executeQuery("SELECT data, attivita FROM rb_attivita_sostegno WHERE anno = {$this->year->get_ID()} AND alunno = {$this->studentData['id']}");
		$this->studentData['act'] = $this->activities;
	}

	private function registerRecordBook($cls, $st){
		$file = "registro-sostegno_{$this->year->get_ID()}_{$this->teacher->getUid()}_{$cls}_{$st}.pdf";
		/*
		 * check for existing record
		*/
		$exist = $this->existsRecordBook($cls, $st);
		if ($exist) {
			$q = "UPDATE rb_registri_personali SET file = '{$file}', data_creazione = NOW() WHERE id = {$exist}";
		}
		else {
			$q = "INSERT INTO rb_registri_personali (anno, docente, classe, alunno, file, data_creazione) VALUES ({$this->year->get_ID()}, {$this->teacher->getUid()}, {$cls}, {$st}, '{$file}', NOW())";
		}
		$this->datasource->executeUpdate($q);
	}

	public function getRecordBook($cls, $st){
		return "registro-sostegno_{$this->year->get_ID()}_{$this->teacher->getUid()}_{$cls}_{$st}.pdf";
	}

	public function existsRecordBook($cls, $st){
		$sel_ex = "SELECT id FROM rb_registri_personali WHERE anno = {$this->year->get_ID()} AND docente = {$this->teacher->getUid()} AND classe = {$cls} AND materia = {$st}";
		$exists = $this->datasource->executeCount($sel_ex);
		return $exists;
	}

} 
