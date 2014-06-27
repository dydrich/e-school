<?php

require_once 'PrimarySchoolTeacherRecordBookPDF.php';
require_once 'TeacherRecordBook.php';
require_once 'ArrayMultiSort.php';
require_once 'RBUtilities.php';

class PrimarySchoolTeacherRecordBook extends TeacherRecordBook{

	private $studentsData;
	private $lessons;
	private $studentsAbsences;
	protected $attachments;

	public function __construct($teacher, $ds, $pt, $y, $p, $sy){
		$this->teacher = $teacher;
		$this->datasource = $ds;
		$this->path = $pt;
		$this->year = $y;
		$this->pubbID = $p;
		$this->schoolYear = $sy;
		$this->attachments = array();
		$this->setRecordBooks();
		$this->pdf = new PrimarySchoolTeacherRecordBookPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->setPath($this->path);
		$this->pdf->setDatasource($ds);
		$this->pdf->setYear($y);
	}

	public function setCls($cls){
		$this->cls = $cls;
	}

	public function loadAttachments($cls, $subject){
		$id = $this->existsRecordBook($cls, $subject);
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
		$classi = $this->teacher->getClasses();
		/*
		 $msarray = new ArrayMultiSort($classi);
		$msarray->setSortFields(array("classe"));
		$msarray->sort();
		$classi = $msarray->getData();
		*/
		$this->recordBooks = array();
		foreach ($classi as $k => $cl){
			if ($cl['teacher']){
				$this->recordBooks[$k] = array("id" => $k, "name" => $cl['classe'], "subjects" => array());
				foreach ($cl['materie'] as $s){
					$sel_sub = "SELECT materia FROM rb_materie WHERE id_materia = {$s} AND pagella = '1'";
					$sub = $this->datasource->executeCount($sel_sub);
					if ($sub){
						$this->recordBooks[$k]['subjects'][$s] = array("mat" => $sub, "exist" => $this->existsRecordBook($k, $s), "file" => $this->getRecordBook($k, $s));
					}
				}
			}
		}
	}

	public function getRecordBooks(){
		return $this->recordBooks;
	}

	public function loadStudentsData($cls, $subject){
		$st = $this->datasource->executeQuery("SELECT rb_alunni.id_alunno, cognome, nome, rb_esiti.esito, positivo FROM rb_alunni, rb_pagelle, rb_esiti WHERE rb_pagelle.esito = id_esito AND id_pubblicazione = {$this->pubbID} AND rb_alunni.id_classe = {$cls} AND rb_alunni.id_alunno = rb_pagelle.id_alunno AND attivo = '1' ORDER BY cognome, nome");
		//$st = $this->datasource->executeQuery($sel_students);
		//echo "SELECT rb_alunni.id_alunno, cognome, nome, rb_esiti.esito, positivo FROM rb_alunni, rb_pagelle, rb_esiti WHERE rb_pagelle.esito = id_esito AND id_pubblicazione = {$this->pubbID} AND rb_alunni.id_classe = {$cls} AND rb_alunni.id_alunno = rb_pagelle.id_alunno AND attivo = '1' ORDER BY cognome, nome";
		$students = array();
		foreach ($st as $s){
			$students[$s['id_alunno']] = array("id" => $s['id_alunno'], "cognome" => $s['cognome'], "nome" => $s['nome'], "esito" => $s['esito'], "positivo" => $s['positivo']);
		}
		$sel_grades = "SELECT * FROM rb_scrutini WHERE classe = {$cls} AND anno = {$this->year->get_ID()} AND materia = {$subject} ORDER BY alunno";
		$rows = $this->datasource->executeQuery($sel_grades);

		foreach ($rows as $row){
			if (isset($students[$row['alunno']])){
				if ($row['quadrimestre'] == 1){
					$students[$row['alunno']]['voto1q'] = $row['voto'];
				}
				else if ($row['quadrimestre'] == 2){
					$students[$row['alunno']]['voto2q'] = $row['voto'];
				}
			}
		}
		$sel_comp = "SELECT alunno, voto, quadrimestre FROM rb_scrutini WHERE classe = {$cls} AND anno = {$this->year->get_ID()} AND materia = 40 ORDER BY alunno";
		$res_comp = $this->datasource->executeQuery($sel_comp);

		foreach ($res_comp as $res){
			if (isset($students[$res['alunno']])){
				if ($res['quadrimestre'] == 1){
					$students[$res['alunno']]['comp1q'] = $res['voto'];
					//echo $grades[$res['alunno']]['comp1q']."<br>";
				}
				else if ($res['quadrimestre'] == 2){
					$students[$res['alunno']]['comp2q'] = $res['voto'];
				}
			}
		}
		$this->studentsData = $students;

		return $students;
	}

	public function loadLessons($cls, $subject){
		$sel_lessons = "SELECT rb_reg_firme.*, data, materia, docente, id_classe FROM rb_reg_firme, rb_reg_classi WHERE rb_reg_classi.id_reg = id_registro AND rb_reg_classi.id_classe = {$cls} AND materia = {$subject} AND docente = {$this->teacher->getUid()} AND anno = {$this->year->get_ID()} ORDER BY data";
		$lessons = $this->datasource->executeQuery($sel_lessons);

		$utils = RBUtilities::getInstance($this->datasource);
		$_cls = $utils->loadClassFromClassID($cls);
		$hmod = $_cls->get_modulo_orario();

		$cont = 0;
		
		$this->lessons = $lessons;
		//print_r($this->studentsData);
	}

	public function loadGrades($cls, $subject){
		$fine_q = format_date($this->schoolYear->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		foreach ($this->studentsData as $k => $st){
			$this->studentsData[$k]['grades1q'] = array();
			$this->studentsData[$k]['grades2q'] = array();
			$sel_marks = "(SELECT data_voto AS data, voto, modificatori, tipologia, descrizione FROM rb_voti WHERE materia = {$subject} AND anno = {$this->year->get_ID()} AND alunno = {$k} ORDER BY data_voto DESC)";
			$sel_marks .=  " UNION (SELECT data AS data, 'impreparato' AS voto, '' AS modificatori, 2 AS tipologia, 'Interrogazione' AS descizione FROM rb_note_didattiche WHERE tipo = 1 AND materia = {$subject} AND anno = {$this->year->get_ID()} AND alunno = {$k} ORDER BY data  DESC)";
			$marks = $this->datasource->executeQuery($sel_marks);
			$rows1 = array();
			$rows2 = array();
			foreach ($marks as $row){
				$ar = array("data" => $row['data'], "voto" => $row['voto'], "mod" => $row['modificatori'], "tipologia" => $row['tipologia'], "desc" => $row['descrizione']);
				if ($row['data'] <= $fine_q){
					$rows1[] = $ar;
				}
				else {
					$rows2[] = $ar;
				}
			}
			$msarray = new ArrayMultiSort($rows1);
			$msarray->setSortFields(array("data"));
			$msarray->sort();
			$this->studentsData[$k]['grades1q'] = $msarray->getData();
			$msarray2 = new ArrayMultiSort($rows2);
			$msarray2->setSortFields(array("data"));
			$msarray2->sort();
			$this->studentsData[$k]['grades2q'] = $msarray2->getData();
		}
	}

	public function createRecordBook($cls, $subject){
		$mat = $this->datasource->executeCount("SELECT materia FROM rb_materie WHERE id_materia = {$subject}");
		$desc = $this->datasource->executeCount("SELECT CONCAT(anno_corso, sezione) FROM rb_classi WHERE id_classe = {$cls}");
		$_sub = array("id" => $subject, "mat" => $mat);
		$_cls = array("id" => $cls, "cls" => $desc);
		$students = $this->loadStudentsData($cls, $subject);
		@$this->loadGrades($cls, $subject);
		$this->loadLessons($cls, $subject);
		@$this->loadAttachments($cls, $subject);
		$this->pdf->init($this->teacher, $_cls, $this->pubbID, $mat, $this->attachments);
		$this->registerRecordBook($cls, $subject);
		return $this->pdf->createRecordBook($this->teacher, $_cls, $_sub, $this->studentsData, $this->lessons);
	}

	public function createWholeRecordBook(){

	}

	private function registerRecordBook($cls, $subject){
		$file = "registro_{$this->year->get_ID()}_{$this->teacher->getUid()}_{$cls}_{$subject}.pdf";
		/*
		 * check for existing record
		*/
		$exist = $this->existsRecordBook($cls, $subject);
		if ($exist) {
			$q = "UPDATE rb_registri_personali SET file = '{$file}', data_creazione = NOW() WHERE id = {$exist}";
		}
		else {
			$q = "INSERT INTO rb_registri_personali (anno, docente, classe, materia, file, data_creazione) VALUES ({$this->year->get_ID()}, {$this->teacher->getUid()}, {$cls}, {$subject}, '{$file}', NOW())";
		}
		$this->datasource->executeUpdate($q);
	}

	public function getRecordBook($cls, $subject){
		return "registro_{$this->year->get_ID()}_{$this->teacher->getUid()}_{$cls}_{$subject}.pdf";
	}

	public function existsRecordBook($cls, $subject){
		$sel_ex = "SELECT id FROM rb_registri_personali WHERE anno = {$this->year->get_ID()} AND docente = {$this->teacher->getUid()} AND classe = {$cls} AND materia = {$subject}";
		$exists = $this->datasource->executeCount($sel_ex);
		return $exists;
	}

}