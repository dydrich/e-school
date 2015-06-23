<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 01/02/14
 * Time: 16.35
 */

require_once "../../../lib/start.php";
require_once "../../../lib/SchoolPDF.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$anno = $_SESSION['__current_year__']->get_ID();
$q = $_REQUEST['q'];
$abs = $_REQUEST['abs'];

switch($q){
	case 1:
		$label = " primo quadrimestre";
		break;
	case 2:
		$label = " secondo quadrimestre";
		break;
}

$grades_only = false;
if(isset($_REQUEST['abs']) && $_REQUEST['abs'] == "0"){
	$grades_only = false;
}

$sel_alunni = "SELECT cognome, nome, id_alunno FROM rb_alunni WHERE id_classe = ". $_SESSION['__classe__']->get_ID() ." ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}

$sel_cls = "SELECT CONCAT(anno_corso, sezione) AS cls FROM rb_classi WHERE id_classe = ".$_SESSION['__classe__']->get_ID();
$cls = $db->executeCount($sel_cls);

$num_colonne = 1;
$first_column_width = 23;
$column_width = null;
$available_space = 100 - $first_column_width;
$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE id_materia = rb_scrutini.materia AND classe = {$_SESSION['__classe__']->get_ID()} AND quadrimestre = {$q} AND anno = {$anno} AND tipologia_scuola = {$ordine_scuola} GROUP BY rb_materie.id_materia, rb_materie.materia ORDER BY rb_materie.posizione_pagella";
$res_materie = $db->executeQuery($sel_materie);
$num_materie = $res_materie->num_rows;
$num_colonne += ($num_materie * 2);
$column_width = intval($available_space / ($num_colonne - 1));
$materie = array();
$comp = array();
while($materia = $res_materie->fetch_assoc()){
	if($materia['id_materia'] == 2){
		$comp = $materia;
	}
	else{
		$materie[] = $materia;
	}
}
if($comp){
	$materie[] = $comp;
}

$alunni = array();

while($al = $res_alunni->fetch_assoc()){
	$alunni[$al['id_alunno']] = array();
	$alunni[$al['id_alunno']]['alunno'] = $al;
	$alunni[$al['id_alunno']]['voti'] = array();

	$sel_voti = "SELECT COALESCE(voto, 0) AS voto, assenze, rb_scrutini.materia FROM rb_scrutini, rb_materie WHERE rb_scrutini.materia = id_materia AND alunno = ".$al['id_alunno']." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = $q ORDER BY rb_materie.posizione_pagella";
	//print $sel_voti;

	try{
		$res_voti = $db->executeQuery($sel_voti);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	$sum = $corrected_sum = 0;
	$num_mat = $num_materie - 1;
	$num_mat = 0;

	while ($voto = $res_voti->fetch_assoc()){
		$alunni[$al['id_alunno']]['voti'][$voto['materia']] = $voto;
		if($voto['materia'] != 26 && $voto['materia'] != 30 && $voto['materia'] != 40){
			$sum += $voto['voto'];
			if ($voto['voto'] != 0) {
				if ($voto['voto'] < 6) {
					$corrected_sum += 6;
				}
				else {
					$corrected_sum += $voto['voto'];
				}
				$num_mat++;
			}
		}
	}
	$avg = $corrected_avg = 0;
	if ($num_mat > 0) {
		$avg = $sum / $num_mat;
		$corrected_avg = $corrected_sum / $num_mat;
	}
	$alunni[$al['id_alunno']]['media'] = round($avg, 2);
	$alunni[$al['id_alunno']]['media_corretta'] = round($corrected_avg, 2);
}

class MYPDF extends SchoolPDF {

	private $y_position = 0.0;

	function page($cls, $q, $materie, $alunni, $print_absences = false, $ordine_scuola){
		$quad = "primo";
		if ($q == 2){
			$quad = "secondo";
		}
		$voti_religione = array(4 => "I", 6 => "S", 8 => "B", 9 => "D", 10 => "O");
		/*
		 * voti di comportamento scuola primaria
		 */
		$voti_comportamento_primaria = array("4" => array("nome" => "non adeguato", "codice" => "NA"),
			"5" => array("nome" => "parzialmente adeguato", "codice" => "PA"),
			"6" => array("nome" => "adeguato", "codice" => "AD")
		);

		$this->SetY(25.0);
		$this->SetFont('helvetica', 'B', 10);
		//$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->Cell(180, 4, $_SESSION['__current_year__']->to_string()."  - Dettaglio dello scrutinio del ".$quad." quadrimestre - Classe {$cls} ", 0, 1, "C", 0);
		$this->setCellPaddings(0, 0, 0, 3);
		$this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));

		if (!$print_absences){
			$this->SetY(40.0);
			$num_colonne = count($materie) + 1;
			$col_w = 140 / $num_colonne;
			$col_w = intval($col_w);
			$this->SetFont('helvetica', 'B', 9);
			$this->Cell(45, 5, "   Alunno", 1, 0);
			foreach ($materie as $mat){
				$this->Cell($col_w, 5, strtoupper(substr($mat['materia'], 0, 3)), 1, 0, 'C');
			}
			$this->Ln();
			$this->SetFont('helvetica', '', 9);
			foreach ($alunni as $alunno){
				$this->SetTextColor(0);
				$this->Cell(45, 5, "   ".$alunno['alunno']['cognome']." ".substr($alunno['alunno']['nome'], 0, 1).". (".$alunno['media']."/".$alunno['media_corretta'].")", 1, 0);
				foreach ($alunno['voti'] as $k => $voto){
					if ($ordine_scuola != 2 && $voto['voto'] < 6 && $voto['voto'] > 0){
						$this->SetTextColor(255, 0, 0);
					}
					else {
						$this->SetTextColor(0);
					}
					if (($voto['materia'] == 26 || $voto['materia'] == 30) && $voto['voto'] != 0 ){
						$voto['voto'] = $voti_religione[$voto['voto']];
					}
					if ($voto['voto'] == "0") $voto['voto'] = "/";
					if ($ordine_scuola == 1){
						$this->Cell($col_w, 5, $voto['voto'], 1, 0, 'C');
					}
					else {
						if ($voto['materia'] == 40){
							$this->Cell($col_w, 5, $voti_comportamento_primaria[$voto['voto']]['codice'], 1, 0, 'C');
						}
						else {
							$this->Cell($col_w, 5, $voto['voto'], 1, 0, 'C');
						}
					}
					$this->SetTextColor(0);
				}
				$this->Ln();
			}
		}
		else {
			$this->SetY(35.0);
			$this->setCellPaddings(0, 0, 0, 1.5);
			$num_colonne = count($materie) - 1;
			$col_w = 220 / $num_colonne;
			$col_w = intval($col_w);
			$this->SetFont('helvetica', 'B', 9);
			$this->Cell(40, 3, "   Alunno", 1, 0);
			foreach ($materie as $mat){
				if ($mat['id_materia'] != 2 && $mat['id_materia'] != 40){
					$this->Cell($col_w, 3, strtoupper(substr($mat['materia'], 0, 3)), 1, 0, 'C');
				}
				else {
					$this->Cell(10, 3, strtoupper(substr($mat['materia'], 0, 3)), 1, 0, 'C');
				}
			}
			$this->Ln();
			$this->SetFont('helvetica', '', 9);
			foreach ($alunni as $alunno){
				$this->SetTextColor(0);
				$this->Cell(40, 2, "   ".$alunno['alunno']['cognome']." ".substr($alunno['alunno']['nome'], 0, 1).". (".$alunno['media']."/".$alunno['media_corretta'].")", 1, 0);
				foreach ($alunno['voti'] as $k => $voto){
					if ($voto['voto'] < 6 && $voto['voto'] > 0){
						$this->SetTextColor(255, 0, 0);
					}
					else {
						$this->SetTextColor(0);
					}
					if (($voto['materia'] == 26 || $voto['materia'] == 30) && $voto['voto'] != 0 ){
						$voto['voto'] = substr($voti_religione[$voto['voto']], 0, 1);
					}
					if ($voto['voto'] == "0") $voto['voto'] = "/";
					if ($voto['materia'] != 2 && $voto['materia'] != 40){
						$this->SetFont('helvetica', 'B', 9);
						$this->Cell($col_w / 2, 2, $voto['voto'], 1, 0, 'C');
						$this->SetTextColor(0);
						$this->SetFont('helvetica', '', 9);
						$this->Cell($col_w / 2, 2, $voto['assenze'], 1, 0, 'C');
					}
					else {
						$this->SetFont('helvetica', 'B', 9);
						$this->Cell(10, 2, $voto['voto'], 1, 0, 'C');
						$this->SetFont('helvetica', '', 9);
					}
				}
				$this->Ln();
			}
		}


	}
}

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("Istituto comprensivo Nivola");
$pdf->SetTitle("Riepilogo scrutinio classe ".$cls);

// set default header data
$school_order = "scuola secondaria di primo grado";
if ($ordine_scuola == 2){
	$school_order = "scuola primaria";
}
$pdf->SetHeaderData("", 0, $_SESSION['__config__']['intestazione_scuola']." - ".$school_order, $_SESSION['__config__']['indirizzo_scuola']);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8.0));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8.0));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

$pdf->SetLineWidth(0.1);

// add a page
if ($abs){
	$pdf->AddPage("L");
}
else {
	$pdf->AddPage("P");
}

$pdf->page($cls, $q, $materie, $alunni, $abs, $ordine_scuola);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('riepilogo_scrutinio.pdf', 'D');
