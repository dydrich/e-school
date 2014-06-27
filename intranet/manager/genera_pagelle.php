<?php

require_once "../../lib/start.php";
require_once "../../lib/SchoolPDF.php";

class MYPDF extends SchoolPDF {

	public function createFirstPage($st){
		$final_letter = "o";
		if($st['sesso'] == "F"){
			$final_letter = "a";
		}
		$this->setPage(1, true);
		$this->Image('../../images/ministero.jpg', 90, 8, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('times', 'B', '24');
		$this->Cell(0, 30, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '16');
		$this->Cell(0, 10, "ISTITUTO COMPRENSIVO IGLESIAS SUD EST", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 15, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 25, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '18');
		$this->Cell(0, 10, "SCHEDA PERSONALE", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 5, "Iscritt{$final_letter} alla classe SECONDA, sezione B", 0, 1, 'C', 0, '', 0);	
	}

	public function report($st, $vt) {
		 
		$this->setPage(2, true);
		$this->SetFont('', 'B');
		$this->Write(9, $_SESSION['__current_year__']->to_string()."  -  Classe ".$st['anno_corso'].$st['sezione'], $align='C');
		$this->SetY(40.0);
		$this->Write(9, "Scheda di valutazione di ".$st['cognome']." ".$st['nome'], $align='L');
		$this->SetY(50.0);
		// Colors, line width and bold font
		$this->SetFillColor(131, 2, 29);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B', '13');
		// Header
		$w = array(100, 30);
		$this->Cell(100, 7, "Materia", 1, 0, 'C', 1);
		$this->Cell(30, 7, "Voto 1Q", 1, 0, 'C', 1);
		$this->Cell(30, 7, "Voto 2Q", 1, 0, 'C', 1);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		$max = $vt->num_rows;
		while ($row = $vt->fetch_assoc()){
			$this->setCellPaddings(10, 0, 0, 0);
			$this->Cell(100, 6, $row['desc_mat'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);
			$this->Cell(30, 6, $row['voto'], 'LR', 0, 'C', $fill);
			$this->Cell(30, 6, '', 'LR', 0, 'C', $fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(160, 0, '', 'T');
		$this->SetFont('helvetica', '', 12);
		$this->SetY(160.0);
		$this->Write(9, "Il coordinatore", $align='L');
		$this->SetX(80.0);
		$this->Write(9, "Il segretario", $align='L');
		$this->SetX(140.0);
		$this->Write(9, "Il Dirigente scolastico", $align='L');
	}
}

function createPDFFile($student, $voti, $file){
	// create new PDF document
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($author);
	$pdf->SetTitle('Scheda di valutazione');
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
	// set font
	$pdf->SetFont('helvetica', '', 12);
	
	$pdf->setJPEGQuality(75);
	
	// add 2 pages
	$pdf->AddPage("P", "A4");
	$pdf->AddPage("P", "A4");
	
	$pdf->createFirstPage($student);
	
	$pdf->report($student, $voti);
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output($file, 'F');
}

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

header("Content-type: text/plain");

$year = $_SESSION['__current_year__']->get_ID();
$q = $_POST['q'];
$id_pubblicazione = 0;
try {
	$id_pubblicazione = $db->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$year} AND quadrimestre = {$q}");
} catch (MySQLException $ex){
	echo "kosql|".$ex->getQuery()."|".$ex->getMessage();
	exit;
}

$dir = $_SESSION['__path_to_root__']."download/pagelle/";
$file_prefix = "{$year}_{$q}";

$students = "SELECT id_alunno, nome, cognome, sesso, rb_alunni.id_classe, anno_corso, sezione FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND attivo = '1' AND ordine_di_scuola = ".$_SESSION['__school_order__'];
try {
	$res_students = $db->executeQuery($students);
} catch (MySQLException $ex){
	echo "kosql|".$ex->getQuery()."|".$ex->getMessage();
	exit;
}
$stn = $res_students->num_rows;

while ($student = $res_students->fetch_assoc()){
	$desc_class = $student['anno_corso'].$student['sezione'];
	$sel_voti = "SELECT rb_scrutini.*, rb_materie.materia AS desc_mat FROM rb_scrutini, rb_materie WHERE alunno = {$student['id_alunno']} AND anno = {$year} AND quadrimestre = {$q} AND rb_scrutini.materia = id_materia";
	try {
		$res_voti = $db->executeQuery($sel_voti);
	} catch (MySQLException $ex){
		echo "kosql|".$ex->getQuery()."|".$ex->getMessage();
		exit;
	}
	$basefile = "{$file_prefix}_{$student['id_classe']}_{$student['id_alunno']}.pdf";
	$file = "{$dir}{$file_prefix}_{$student['id_classe']}_{$student['id_alunno']}.pdf";
	createPDFFile($student, $res_voti, $file);
	try {
		$db->executeQuery("UPDATE rb_pagelle SET id_file = '{$basefile}', desc_classe = '{$desc_class}' WHERE id_pubblicazione = {$id_pubblicazione} AND id_alunno = {$student['id_alunno']}");
	} catch (MySQLException $ex){
		echo "kosql|".$ex->getQuery()."|".$ex->getMessage();
		exit;
	}
}

echo "ok";
exit;
