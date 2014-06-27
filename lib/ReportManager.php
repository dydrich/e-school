<?php

require_once "classes.php";
require_once "data_source.php";
require_once "ReportPDF.php";
require_once "FinalReportPDF.php";
require_once "PrimarySchoolReportPDF.php";

class ReportManager {
	
	private $datasource = null;
	private $year = null;
	private $schoolOrder = 0;
	public $FINAL_REPORT = 2;
	public $SESSION_REPORT = 1;
	
	function __construct($ds, $year, $so){
		$this->datasource = new MySQLDataLoader($ds);
		$this->year = $year;
		$this->schoolOrder = $so;
	}
	
	public function searchReport($type, $params){
		if($type == $this->FINAL_REPORT){
			/**
			TODO: check and clean
			 */
			$year = $_REQUEST['y'];
			
			$sel_pubb = "SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$year} AND quadrimestre = 2";
			$pubb = array();
			$res_pubb = $this->datasource->executeQuery($sel_pubb);
			foreach ($res_pubb as $p){
				$pubb[] = $p;
			}
			$string_pubb = join(",", $pubb);
			
			$sel_stds = "SELECT cognome, nome, data_nascita, luogo_nascita, rb_alunni.id_alunno AS alunno, esito, id_file, id_pagella FROM rb_alunni, rb_pagelle WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno AND id_pubblicazione IN ({$string_pubb}) ";
			if (isset($_POST['ord']) && $_POST['ord'] != 0){
				$sel_stds .= " AND ";
			}
			if(isset($_POST['lname']) && $_POST['lname'] != ""){
				$sel_stds .= " AND cognome LIKE '{$_POST['lname']}%'";
			}
			if(isset($_POST['cls']) && $_POST['cls'] != 0){
				$sel_stds .= " AND rb_alunni.id_classe = {$_POST['cls']}";
			}
			$sel_stds .= " ORDER BY cognome, nome, id_file DESC";
			//echo $sel_stds;
			$res_stds = $this->datasource->executeQuery($sel_stds);
			if (count($res_stds) < 1){
				echo "nostd#{$sel_stds}";
				exit;
			}
			$json = array();
			foreach ($res_stds as $std){
				$json[$std['alunno']] = array("id" => $std['alunno'], "nome" => $std['cognome']." ".$std['nome'], "esito" => $std['esito'], "file" => $std['id_file'], "id_pagella" => $std['id_pagella'], "del" => 0);
			}
			echo json_encode($json);
			exit;
		}
		else if ($type == $this->SESSION_REPORT){
			$sel_stds = "SELECT cognome, nome, data_nascita, luogo_nascita, rb_alunni.id_alunno AS alunno, sesso, rb_alunni.id_classe, anno_corso, sezione FROM rb_alunni, rb_classi WHERE attivo = '1' AND rb_alunni.id_classe = rb_classi.id_classe ";
			if($params['lname'] != ""){
				$sel_stds .= " AND cognome LIKE '{$params['lname']}%'";
			}
			if($params['cls'] != 0){
				$sel_stds .= " AND rb_alunni.id_classe = {$params['cls']}";
			}
			$sel_stds .= " ORDER BY cognome, nome ";
			$res_stds = $this->datasource->executeQuery($sel_stds);
			if (!$res_stds || count($res_stds) < 1){
				return false;
			}
			$i = 0;
			foreach ($res_stds as $row){
				/*
				$sel_f = "SELECT id_file FROM rb_pagelle, rb_pubblicazione_pagelle WHERE rb_pagelle.id_pubblicazione = rb_pubblicazione_pagelle.id_pagella AND anno = {$this->year} AND quadrimestre = 1 AND id_alunno = {$row['alunno']} ";
				$file = $this->datasource->executeCount($sel_f);
				*/
				$file = $this->createOnFlyReport(1, $row);
				$res_stds[$i]['file'] = $file;
				$res_stds[$i]['id'] = $row['alunno'];
				$i++;
			}
			return $res_stds;
		}
		return false;
	}
	
	/**
	 * 
	 * create on-fly report and return file name for download
	 * 
	 * @param integer $session
	 * @param array $student
	 * @return string $filename
	 */
	public function createOnFlyReport($session, $student){
		$id_religione = 26;
		if ($this->schoolOrder == 2){
			$id_religione = 30;
		}
		$id_pubblicazione = 0;
		$id_pubblicazione = $this->datasource->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$this->year} AND quadrimestre = {$session}");
				
		$dir = $_SESSION['__path_to_root__']."tmp/";
		$file_prefix = "{$this->year}_{$session}";
		
		$desc_class = $student['anno_corso'].$student['sezione'];
		$sel_voti = "SELECT rb_scrutini.*, rb_materie.materia AS desc_mat FROM rb_scrutini, rb_materie WHERE alunno = {$student['alunno']} AND anno = {$this->year} AND quadrimestre = 1 AND rb_scrutini.materia = id_materia AND id_materia != {$id_religione} ORDER BY posizione_pagella ";
		$voti = $this->datasource->executeQuery($sel_voti);
		
		$sel_religione = "SELECT voto FROM rb_scrutini, rb_materie WHERE alunno = {$student['alunno']} AND anno = {$this->year} AND quadrimestre = 1 AND rb_scrutini.materia = {$id_religione} ";
		$voto_religione = $this->datasource->executeCount($sel_religione);

		$sel_cdc = "SELECT uid, nome, cognome, materia FROM rb_utenti, rb_docenti, rb_cdc WHERE uid = rb_docenti.id_docente AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_anno = {$this->year} AND rb_cdc.id_classe = ".$student['id_classe']." ORDER BY cognome, nome";
		$res_cdc = $this->datasource->executeQuery($sel_cdc);
		$num_docenti = count($res_cdc);
		$ids = array();
		$cdc = "";
		$doc_religione = null;
		if($num_docenti > 0){
			foreach ($res_cdc as $doc){
				if ($doc['materia'] != $id_religione){
					if(!in_array($doc['uid'], $ids)){
						$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
					}
				}
				else {
					$doc_religione = $doc['cognome']." ".$doc['nome'];
				}
				$ids[] = $doc['uid'];
			}
		}
		$sel_sos = "SELECT uid, nome, cognome FROM rb_utenti, rb_assegnazione_sostegno WHERE uid = rb_assegnazione_sostegno.docente AND rb_assegnazione_sostegno.anno = {$this->year} AND rb_assegnazione_sostegno.classe = ".$student['id_classe'];
		$res_sos = $this->datasource->executeQuery($sel_sos);
		if($res_sos && count($res_sos) > 0){
			foreach ($res_sos as $doc){
				if(!in_array($doc['uid'], $ids)){
					$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
				}
				$ids[] = $doc['uid'];
			}
		}
		$cdc = substr($cdc, 0, (strlen($cdc) - 2));
		
		// parametri
		if ($this->schoolOrder == 2){
			$sel_param = "SELECT * FROM rb_parametri_pagella WHERE ordine_scuola = {$this->schoolOrder}";
			$res_param = $this->datasource->executeQuery($sel_param);
		
			$sel_vals = "SELECT rb_valutazione_parametri_pagella.*, rb_giudizi_parametri_pagella.giudizio as giudizio_g FROM rb_valutazione_parametri_pagella, rb_giudizi_parametri_pagella WHERE studente = {$student['alunno']} AND rb_valutazione_parametri_pagella.giudizio = rb_giudizi_parametri_pagella.id AND anno = {$this->year} AND quadrimestre = 1";
			$res_vals = $this->datasource->executeQuery($sel_vals);
			$vals = array();
			if ($res_vals && count($res_vals) > 0){
				foreach ($res_vals as $r){
					$vals[$r['parametro']] = $r['giudizio_g'];
				}
			}
		}
		
		$basefile = "{$file_prefix}_{$student['id_classe']}_{$student['alunno']}.pdf";
		$file = "{$dir}{$file_prefix}_{$student['id_classe']}_{$student['alunno']}.pdf";
		//echo $file;
		
		if ($this->schoolOrder == 1){
			$pdf = new ReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}
		else {
			$pdf = new PrimarySchoolReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("");
		$pdf->SetTitle('Scheda di valutazione');
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		//$pdf->setLanguageArray($l);
		$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
		$pdf->SetFont('helvetica', '', 12);
		$pdf->AddPage("P", "A4");
		$pdf->AddPage("P", "A4");
		$voti_religione = array("0" => "NC", "4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
		if ($voto_religione == ""){
			$voto_religione = 0;
		}
		if ($this->schoolOrder == 2){
			$pdf->onFlyReport($student, $voti, $voti_religione[$voto_religione], $res_param, $vals, $cdc, $doc_religione);
		}
		else {
			$pdf->onFlyReport($student, $voti, $voti_religione[$voto_religione], $cdc, $doc_religione);
		}
		$pdf->Output($file, 'F');
		
		return $basefile;
	}
	
	/**
	 * 
	 * @param $backup: if true, this is a backup copy and have to create zip file
	 */
	public function createFinalReports($backup = false){
		$id_religione = 26;
		$final_dir = "scuola-secondaria";
		if ($this->schoolOrder == 2){
			$id_religione = 30;
			$final_dir = "scuola-primaria";
		}
		$id_pubblicazione = 0;
		$id_pubblicazione = $this->datasource->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$this->year} AND quadrimestre = 2");

		$str_year = (date("Y") - 1)."-".date("Y");
		$dir = $_SESSION['__path_to_root__']."download/pagelle/{$str_year}/{$final_dir}/";
		if ($backup){
			$str_year = date("Y");
			$dir = $_SESSION['__path_to_root__']."tmp/pagelle_{$str_year}_2Q/";
		}
		@mkdir($dir, 0777, true);

		$file_prefix = "{$str_year}_2Q";
		if ($backup){
			$file_prefix = "";
		}

		$students = "SELECT id_alunno, nome, cognome, data_nascita, luogo_nascita, sesso, rb_alunni.id_classe, anno_corso, sezione FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND attivo = '1' AND ordine_di_scuola = ".$this->schoolOrder;
		$res_students = $this->datasource->executeQuery($students);
		$stn = count($res_students);
		
		// parametri
		if ($this->schoolOrder == 2){
			$sel_param = "SELECT * FROM rb_parametri_pagella WHERE ordine_scuola = {$this->schoolOrder}";
			$res_param = $this->datasource->executeQuery($sel_param);
		}
		
		foreach ($res_students as $student){
			$student_name = $student['cognome']."-".$student['nome'];
			$student_name = preg_replace("/ /", "", $student_name);
			$student_name = preg_replace("/\'/", "", $student_name);

			$sel_cdc = "SELECT uid, nome, cognome, materia FROM rb_utenti, rb_docenti, rb_cdc WHERE uid = rb_docenti.id_docente AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_anno = {$this->year} AND rb_cdc.id_classe = ".$student['id_classe']." ORDER BY cognome, nome";
			$res_cdc = $this->datasource->executeQuery($sel_cdc);
			$num_docenti = count($res_cdc);
			$ids = array();
			$cdc = "";
			$doc_religione = null;
			if($num_docenti > 0){
				foreach ($res_cdc as $doc){
					if ($doc['materia'] != $id_religione){
						if(!in_array($doc['uid'], $ids)){
							$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
						}
					}
					else {
						$doc_religione = $doc['cognome']." ".$doc['nome'];
					}
					$ids[] = $doc['uid'];
				}
			}
			$sel_sos = "SELECT uid, nome, cognome FROM rb_utenti, rb_assegnazione_sostegno WHERE uid = rb_assegnazione_sostegno.docente AND rb_assegnazione_sostegno.anno = {$this->year} AND rb_assegnazione_sostegno.classe = ".$student['id_classe'];
			$res_sos = $this->datasource->executeQuery($sel_sos);
			if($res_sos && count($res_sos) > 0){
				foreach ($res_sos as $doc){
					if(!in_array($doc['uid'], $ids)){
						$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
					}
					$ids[] = $doc['uid'];
				}
			}
			$cdc = substr($cdc, 0, (strlen($cdc) - 2));

			$desc_class = $student['anno_corso'].$student['sezione'];
			$sel_voti_q1 = "SELECT rb_scrutini.*, rb_materie.materia AS desc_mat, rb_materie.id_materia FROM rb_scrutini, rb_materie WHERE alunno = {$student['id_alunno']} AND anno = {$this->year} AND rb_scrutini.materia = id_materia AND id_materia != {$id_religione} AND quadrimestre = 1 ORDER BY posizione_pagella ";
			$voti_q1 = $this->datasource->executeQuery($sel_voti_q1);
			$sel_voti_q2 = "SELECT rb_scrutini.*, rb_materie.materia AS desc_mat, rb_materie.id_materia FROM rb_scrutini, rb_materie WHERE alunno = {$student['id_alunno']} AND anno = {$this->year} AND rb_scrutini.materia = id_materia AND id_materia != {$id_religione} AND quadrimestre = 2 ORDER BY posizione_pagella ";
			$voti_q2 = $this->datasource->executeQuery($sel_voti_q2);
			/*
			 * religione
			 */
			$sel_religione = "SELECT voto, quadrimestre FROM rb_scrutini, rb_materie WHERE alunno = {$student['id_alunno']} AND anno = {$this->year} AND rb_scrutini.materia = {$id_religione} ORDER BY quadrimestre ASC";
			$vreligione = $this->datasource->executeQuery($sel_religione);
			$voto_rel = array();
			foreach ($vreligione as $v){
				if (!isset($v['voto']) || $v['voto'] == ""){
					//$voto_rel[] = 6;
					$voto_rel[$v['quadrimestre']] = '';
				}
				else{
					$voto_rel[$v['quadrimestre']] = $v['voto'];
				}
			}

			// esito
			$sel_esito = "SELECT desc_pagella, positivo, id_esito FROM rb_pagelle, rb_esiti WHERE rb_pagelle.esito = id_esito AND id_pubblicazione = {$id_pubblicazione} AND id_alunno = {$student['id_alunno']}";
			//echo $sel_esito;
			$esito = $this->datasource->executeQuery($sel_esito);
			@mkdir($dir.$desc_class, 0777, true);
			$basefile = "{$file_prefix}_{$desc_class}_{$student_name}.pdf";
			$file = "{$dir}{$desc_class}/{$file_prefix}_{$desc_class}_{$student_name}.pdf";

			if ($this->schoolOrder == 2){
				$sel_vals = "SELECT rb_valutazione_parametri_pagella.*, rb_giudizi_parametri_pagella.giudizio as giudizio_g FROM rb_valutazione_parametri_pagella, rb_giudizi_parametri_pagella WHERE studente = {$student['id_alunno']} AND rb_valutazione_parametri_pagella.giudizio = rb_giudizi_parametri_pagella.id AND anno = {$this->year} AND quadrimestre = 1";
				$res_vals = $this->datasource->executeQuery($sel_vals);
				$vals1q = array();
				if ($res_vals != null){
					foreach ($res_vals as $r){
						$vals1q[$r['parametro']] = $r['giudizio_g'];
					}
				}
				$sel_vals = "SELECT rb_valutazione_parametri_pagella.*, rb_giudizi_parametri_pagella.giudizio as giudizio_g FROM rb_valutazione_parametri_pagella, rb_giudizi_parametri_pagella WHERE studente = {$student['id_alunno']} AND rb_valutazione_parametri_pagella.giudizio = rb_giudizi_parametri_pagella.id AND anno = {$this->year} AND quadrimestre = 2";
				$res_vals = $this->datasource->executeQuery($sel_vals);
				$vals2q = array();
				if ($res_vals != null){
					foreach ($res_vals as $r){
						$vals2q[$r['parametro']] = $r['giudizio_g'];
					}
				}
			}
			
			/*
			 * create PFDs
			 */
			if ($this->schoolOrder == 1){
				$pdf = new ReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			}
			else {
				$pdf = new PrimarySchoolReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			}
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor("Istituto comprensivo Nivola");
			$pdf->SetTitle('Scheda di valutazione');
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetMargins(PDF_MARGIN_LEFT, 25, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->SetAutoPageBreak(TRUE, 15);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			//$pdf->setLanguageArray($l);
			$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
			$pdf->SetFont('helvetica', '', 12);
			$pdf->setJPEGQuality(75);
			$pdf->AddPage("P", "A4");
			$pdf->AddPage("P", "A4");
			
			if ($this->schoolOrder == 2){
				$pdf->AddPage("P", "A4");
				$pdf->createFirstPage($student, $esito[0], $voti_q1, $voti_q2, $voto_rel, $res_param, $vals1q, $vals2q, $cdc);
			}
			else {
				$pdf->createFirstPage($student, $esito[0], $voti_q1, $voti_q2, $voto_rel, $cdc, $doc_religione);
			}
			//$pdf->report($student, $voti_q1, $voti_q2, $esito);
			$pdf->Output($file, 'F');
			
			/*
			 * disponibilita` pagelle per docenti
			 */
			if ($this->schoolOrder == 2){
				$this->datasource->executeUpdate("UPDATE rb_pubblicazione_pagelle SET disponibili_docenti_sp = NOW() WHERE id_pagella = {$id_pubblicazione}");
			}
			else {
				$this->datasource->executeUpdate("UPDATE rb_pubblicazione_pagelle SET disponibili_docenti = NOW() WHERE id_pagella = {$id_pubblicazione}");
			}
			$this->datasource->executeQuery("UPDATE rb_pagelle SET id_file = '{$basefile}', desc_classe = '{$desc_class}' WHERE id_pubblicazione = {$id_pubblicazione} AND id_alunno = {$student['id_alunno']}");
		}
	}

	public function doBackup($session){
		$id_religione = 26;
		$folder = "scuola_secondaria";
		if ($this->schoolOrder == 2){
			$id_religione = 30;
			$folder = "scuola_primaria";
		}

		$year_desc = $this->datasource->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = {$this->year}");
		$dir = $_SESSION['__path_to_root__']."tmp/{$year_desc}/{$session}/{$folder}";
		if(!file_exists($_SESSION['__config__']['html_root']."/tmp/{$year_desc}/{$session}/{$folder}")){
			mkdir($_SESSION['__config__']['html_root']."/tmp/{$year_desc}/{$session}/{$folder}", 0777, true);
		}

		$sel_students = "SELECT id_alunno, cognome, nome, sesso, anno_corso, sezione, data_nascita, IFNULL(luogo_nascita, '') luogo_nascita, CONCAT(anno_corso, sezione) AS desc_class, rb_classi.id_classe FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND attivo = 1 AND ordine_di_scuola = ".$this->schoolOrder." ORDER BY sezione, anno_corso, cognome, nome";
		$students = $this->datasource->executeQuery($sel_students);

		if ($session == 1){
			foreach ($students as $student){
				$sel_voti = "SELECT rb_scrutini.*, rb_materie.materia AS desc_mat FROM rb_scrutini, rb_materie WHERE alunno = {$student['id_alunno']} AND anno = {$this->year} AND quadrimestre = 1 AND rb_scrutini.materia = id_materia AND id_materia != {$id_religione} ORDER BY posizione_pagella ";
				$voti = $this->datasource->executeQuery($sel_voti);

				$sel_religione = "SELECT voto FROM rb_scrutini, rb_materie WHERE alunno = {$student['id_alunno']} AND anno = {$this->year} AND quadrimestre = 1 AND rb_scrutini.materia = {$id_religione} ";
				$voto_religione = $this->datasource->executeCount($sel_religione);

				$sel_cdc = "SELECT uid, nome, cognome, id_materia FROM rb_utenti, rb_docenti, rb_cdc WHERE uid = rb_docenti.id_docente AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_anno = {$this->year} AND rb_cdc.id_classe = ".$student['id_classe']." ORDER BY cognome, nome";
				$res_cdc = $this->datasource->executeQuery($sel_cdc);
				$num_docenti = count($res_cdc);
				$ids = array();
				$cdc = "";
				$doc_religione = "";
				if($num_docenti > 0){
					foreach ($res_cdc as $doc){
						if ($doc['id_materia'] != $id_religione){
							if(!in_array($doc['uid'], $ids)){
								$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
							}
						}
						else {
							$doc_religione = $doc['cognome']." ".$doc['nome'];
						}
						$ids[] = $doc['uid'];
					}
				}
				$sel_sos = "SELECT uid, nome, cognome FROM rb_utenti, rb_assegnazione_sostegno WHERE uid = rb_assegnazione_sostegno.docente AND rb_assegnazione_sostegno.anno = {$this->year} AND rb_assegnazione_sostegno.classe = ".$student['id_classe'];
				$res_sos = $this->datasource->executeQuery($sel_sos);
				if($res_sos && count($res_sos) > 0){
					foreach ($res_sos as $doc){
						if(!in_array($doc['uid'], $ids)){
							$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
						}
						$ids[] = $doc['uid'];
					}
				}
				$cdc = substr($cdc, 0, (strlen($cdc) - 2));

				// parametri
				if ($this->schoolOrder == 2){
					$sel_param = "SELECT * FROM rb_parametri_pagella WHERE ordine_scuola = 2";
					$res_param = $this->datasource->executeQuery($sel_param);

					$sel_vals = "SELECT rb_valutazione_parametri_pagella.*, rb_giudizi_parametri_pagella.giudizio as giudizio_g FROM rb_valutazione_parametri_pagella, rb_giudizi_parametri_pagella WHERE studente = {$student['id_alunno']} AND rb_valutazione_parametri_pagella.giudizio = rb_giudizi_parametri_pagella.id AND anno = {$this->year} AND quadrimestre = 1";
					$res_vals = $this->datasource->executeQuery($sel_vals);
					$vals = array();
					if ($res_vals && count($res_vals) > 0){
						foreach ($res_vals as $r){
							$vals[$r['parametro']] = $r['giudizio_g'];
						}
					}
				}

				$fname = preg_replace("/ /", "_", $student['nome']);
				$lname = preg_replace("/ /", "_", $student['cognome']);
				$full_name = $lname."_".$fname;
				$basefile = "{$student['desc_class']}-{$full_name}.pdf";
				$file = "{$dir}/{$basefile}";
				//echo $file;

				if ($this->schoolOrder == 1){
					$pdf = new ReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				}
				else {
					$pdf = new PrimarySchoolReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				}
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetAuthor("");
				$pdf->SetTitle('Scheda di valutazione');
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
				//$pdf->setLanguageArray($l);
				$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
				$pdf->SetFont('helvetica', '', 12);
				$pdf->AddPage("P", "A4");
				$pdf->AddPage("P", "A4");
				$voti_religione = array("0" => "NC", "4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
				if ($voto_religione == ""){
					$voto_religione = 0;
				}

				if ($this->schoolOrder == 2){
					$pdf->onFlyReport($student, $voti, $voti_religione[$voto_religione], $res_param, $vals, $cdc, $doc_religione);
				}
				else {
					$pdf->onFlyReport($student, $voti, $voti_religione[$voto_religione], $cdc, $doc_religione);
				}
				$pdf->Output($file, 'F');
			}

			$old_dir = getcwd();
			chdir($_SESSION['__config__']['html_root']."/tmp/{$year_desc}/{$session}/{$folder}/");
			$zip = new ZipArchive();
			$file_zip = $folder."-".$year_desc."-".$session."Q.zip";
			if (file_exists($file_zip)){
				unlink($file_zip);
			}
			if ($zip->open($file_zip, ZipArchive::CREATE)!==TRUE) {
				exit("cannot open <$file_zip>\n");
			}
			$nodes = glob("./*.pdf");
			foreach ($nodes as $node){
				$zip->addFile($node);
			}
			$zip->close();
			chdir($old_dir);
			return $file_zip;
		}
	}
	
}