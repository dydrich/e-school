<?php

ini_set("display_errors", "1");

require_once 'FirstGradeTeacherRecordBookPDF.php';
require_once 'TeacherRecordBook.php';
require_once 'ArrayMultiSort.php';
require_once 'RBUtilities.php';

class FirstGradeTeacherRecordBook extends TeacherRecordBook{
	
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
		$this->pdf = new FirstGradeTeacherRecordBookPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
		$st = $this->datasource->executeQuery("SELECT rb_alunni.id_alunno, cognome, nome, rb_esiti.esito, positivo FROM rb_alunni, rb_pagelle LEFT JOIN rb_esiti ON rb_pagelle.esito = id_esito WHERE id_pubblicazione = {$this->pubbID} AND rb_alunni.id_classe = {$cls} AND rb_alunni.id_alunno = rb_pagelle.id_alunno AND attivo = '1' ORDER BY cognome, nome");
		//echo "SELECT rb_alunni.id_alunno, cognome, nome, rb_esiti.esito, positivo FROM rb_alunni, rb_pagelle, rb_esiti WHERE rb_pagelle.esito = id_esito AND id_pubblicazione = {$this->pubbID} AND rb_alunni.id_classe = {$cls} AND rb_alunni.id_alunno = rb_pagelle.id_alunno AND attivo = '1' ORDER BY cognome, nome";
		$students = array();
		foreach ($st as $s){
			$students[$s['id_alunno']] = array("id" => $s['id_alunno'], "cognome" => $s['cognome'], "nome" => $s['nome'], "esito" => $s['esito'], "positivo" => $s['positivo']);
		}
		$sel_grades = "SELECT * FROM rb_scrutini WHERE classe = {$cls} AND anno = {$this->year->get_ID()} AND materia = {$subject} ORDER BY alunno";
		$rows = $this->datasource->executeQuery($sel_grades);
		
		foreach ($rows as $row){
			if (!$students[$row['alunno']]){
				$students[$row['alunno']] = array();
			}
			if ($row['quadrimestre'] == 1){
				$students[$row['alunno']]['voto1q'] = $row['voto'];
				$students[$row['alunno']]['assenze1q'] = $row['assenze'];
			}
			else if ($row['quadrimestre'] == 2){
				$students[$row['alunno']]['voto2q'] = $row['voto'];
				$students[$row['alunno']]['assenze2q'] = $row['assenze'];
			}
		}
		$sel_comp = "SELECT alunno, voto, quadrimestre FROM rb_scrutini WHERE classe = {$cls} AND anno = {$this->year->get_ID()} AND materia = 2 ORDER BY alunno";
		$res_comp = $this->datasource->executeQuery($sel_comp);
		
		foreach ($res_comp as $res){
			if ($res['quadrimestre'] == 1){
				$students[$res['alunno']]['comp1q'] = $res['voto'];
				//echo $grades[$res['alunno']]['comp1q']."<br>";
			}
			else if ($res['quadrimestre'] == 2){
				$students[$res['alunno']]['comp2q'] = $res['voto'];
			}
		}
		$this->studentsData = $students;
		return $students;
	}
	
	public function loadLessons($cls, $subject){
		$sel_lessons = "SELECT rb_reg_firme.*, data, materia, docente, id_classe FROM rb_reg_firme, rb_reg_classi WHERE rb_reg_classi.id_reg = id_registro AND rb_reg_classi.id_classe = {$cls} AND materia = {$subject} AND anno = {$this->year->get_ID()} ORDER BY data";
		$lessons = $this->datasource->executeQuery($sel_lessons);
		
		$utils = RBUtilities::getInstance($this->datasource);
		$_cls = $utils->loadClassFromClassID($cls);
		$hmod = $_cls->get_modulo_orario();
		
		$cont = 0;
		foreach ($lessons as $les){
			$d = date("w", strtotime($les['data']));
			$day = $hmod->getDay($d);
			$starts = $day->getLessonsStartTime();
			$hstart = $starts[$les['ora']];
			$duration = $day->getHourDuration();
			$end = new RBTime(0, 0, 0);
			$end->setTime($hstart->getTime());
			$end->add($duration->getTime());
			
			$abs = array();
			$absh = array();
			$fine_q = format_date($this->schoolYear->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
			$sel_reg = "SELECT rb_reg_alunni.*, data, nome, cognome FROM rb_reg_alunni, rb_reg_classi, rb_alunni WHERE rb_alunni.id_alunno = rb_reg_alunni.id_alunno AND id_registro = id_reg AND id_registro = {$les['id_registro']} AND rb_reg_alunni.id_classe = {$cls}";
			$rows = $this->datasource->executeQuery($sel_reg);

			foreach ($rows as $row){
				$working_date = "";
				if ($row['ingresso'] == ""){
					if ($les['data'] <= $fine_q){
						if (!isset($this->studentsData[$row['id_alunno']]['dett_abs1'])){
							$this->studentsData[$row['id_alunno']]['dett_abs1'] = array();
						}
					}
					else {
						if (!isset($this->studentsData[$row['id_alunno']]['dett_abs2'])){
							$this->studentsData[$row['id_alunno']]['dett_abs2'] = array();
						}
					}
					$abs[] = $row;
					if ($les['data'] <= $fine_q){
						//echo "1Q###".$les['data']." => ###".$working_date."###";
						if ($working_date != $les['data']){
							//echo "create";
							$this->studentsData[$row['id_alunno']]['dett_abs1'][$les['data']] = array("data" => $les['data'], "time" => 60);
						}
						else {
							$this->studentsData[$row['id_alunno']]['dett_abs1'][$les['data']]['time'] += 60;
						}
					}
					else {
						//echo "2Q###".$les['data']."# => ###".$working_date."###";
						if ($working_date != $les['data']){
							//echo "create";
							$this->studentsData[$row['id_alunno']]['dett_abs2'][$les['data']] = array("data" => $les['data'], "time" => 60);
						}
						else {
							$this->studentsData[$row['id_alunno']]['dett_abs2'][$les['data']]['time'] += 60;
						}
						//$this->studentsData[$row['id_alunno']]['dett_abs2'][] = array("data" => $les['data'], "time" => "");
					}
				}
				else {
					$min = $utils->calcola_minuti_assenza($row['ingresso'], $row['uscita'], $hstart->toString(), $end->toString());
					if ($min > 0){
						if (!isset($this->studentsData[$row['id_alunno']]['dett_abs1'])){
							$this->studentsData[$row['id_alunno']]['dett_abs1'] = array();
						}
						if (!isset($this->studentsData[$row['id_alunno']]['dett_abs2'])){
							$this->studentsData[$row['id_alunno']]['dett_abs2'] = array();
						}
					}
					$dur = $duration->getTime() / 60;
					if ($min > 0 && $min < ($dur)){
						$absh[] = array($row['nome'], $row['cognome'], $min);
						if ($les['data'] <= $fine_q){
							$this->studentsData[$row['id_alunno']]['dett_abs1'][$les['data']] = array("data" => $les['data'], "time" => $min);
						}
						else {
							$this->studentsData[$row['id_alunno']]['dett_abs2'][$les['data']] = array("data" => $les['data'], "time" => $min);
						}
					}
					else if ($min == $dur){
						$abs[] = $row;
					}
				}
				$working_date = $les['data'];
			}
			$lessons[$cont]['abs'] = $abs;
			$lessons[$cont]['absh'] = $absh;
			$cont++;
		}
		$this->lessons = $lessons;
		//print_r($this->studentsData);
	}
	
	public function loadGrades($cls, $subject){
		$fine_q = format_date($this->schoolYear->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		foreach ($this->studentsData as $k => $st){
			$this->studentsData[$k]['grades1q'] = array();
			$this->studentsData[$k]['grades2q'] = array();
			$sel_marks = "(SELECT data_voto AS data, voto, modificatori, tipologia, descrizione FROM rb_voti WHERE materia = {$subject} AND anno = {$this->year->get_ID()} AND alunno = {$k} ORDER BY data_voto DESC)";
			$sel_marks .=  " UNION ALL (SELECT data AS data, 'impreparato' AS voto, '' AS modificatori, 2 AS tipologia, 'Interrogazione' AS descizione FROM rb_note_didattiche WHERE tipo = 1 AND materia = {$subject} AND anno = {$this->year->get_ID()} AND alunno = {$k} ORDER BY data  DESC)";
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
		$subs = $this->checkSubstitution($cls);
		$this->pdf->init($this->teacher, $_cls, $this->pubbID, $mat, $this->attachments);
		$this->registerRecordBook($cls, $subject);
		return $this->pdf->createRecordBook($this->teacher, $_cls, $_sub, $this->studentsData, $this->lessons, $subs);
	}
	
	public function createWholeRecordBook(){
		
	}

	private function checkSubstitution($cls){
		$sel_subs = "SELECT rb_supplenze.*, rb_classi_supplenza.*, CONCAT_WS(' ', cognome, nome) AS doc ";
		$sel_subs .= "FROM rb_supplenze, rb_classi_supplenza, rb_utenti ";
		$sel_subs .= "WHERE rb_supplenze.id_supplenza = rb_classi_supplenza.id_supplenza AND id_supplente = uid ";
		$sel_subs .= "AND id_docente_assente = {$this->teacher->getUid()} AND classe = {$cls} AND anno = {$this->year->get_ID()} ORDER BY data_inizio_supplenza ";
		$res_subs = $this->datasource->executeQuery($sel_subs);
		if ($res_subs) {
			/*
			 * calcolo giorni supplenza
			 */
			$index = 0;
			foreach ($res_subs as $row) {
				$days = $this->datasource->executeCount("SELECT COUNT(id_reg) FROM rb_reg_classi WHERE id_classe = {$cls} AND (data BETWEEN '{$row['data_inizio_supplenza']}' AND '{$row['data_fine_supplenza']}')");
				$res_subs[$index]['days'] = $days;
				$index++;
			}
			return $res_subs;
		}
		else {
			return null;
		}
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
