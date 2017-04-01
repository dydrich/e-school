<?php

require_once "../../../lib/start.php";
require_once "../../../lib/ClassbookData.php";
require_once "../../../lib/SchoolPDF.php";

check_session();
check_permission(DOC_PERM);

if((($_SESSION['__user__']->getSchoolOrder() == 1 && !$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()))) && (!$_SESSION['__user__']->isAdministrator()) && ($_SESSION['__user__']->getUsername() != "rbachis") ){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: ../no_permission.php");
}

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$_tday = date("Y-m-d");
switch($q){
	case 0:
		$par_tot = "AND data <= NOW()";
		$label = "Dati al ".date("d/m/Y");
		break;
	case 1:
		$fq = $fine_q;
		$min = $_tday < $fq ? $_tday : $fq;
		$par_tot = "AND DATA <= '{$min}'";
		$label = "Dati primo quadrimestre";
		break;
	case 2:
		$par_tot = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = "Dati secondo quadrimestre";
		break;
}

$module = $_SESSION['__classe__']->get_modulo_orario();
$classbook_data = new ClassbookData($_SESSION['__classe__'], $school_year, $par_tot, $db);
$totali = $classbook_data->getClassSummary();
$presence = $classbook_data->getStudentsSummary();

setlocale(LC_ALL, "it_IT.utf8");

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {

	private $y_position = 0.0;

	function pageHeader($totali, $label){
		$this->SetY(25.0);
		$this->SetFont('', 'B', 9);
		//$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->Cell(180, 4, $_SESSION['__current_year__']->to_string()."  - Statistiche di assenza per la classe ".$_SESSION['__classe__']->to_string(), 0, 1, "C", 0);
		$this->Cell(180, 4, $label, 0, 1, "C", 0);
		$this->setCellPaddings(0, 0, 0, 3);
		$this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
		$this->Cell(90, 4, "Giorni di lezione: ".$totali['giorni'], "TB", 0, "R", 0);
		$this->Cell(90, 4, "     Ore di lezione: ".$totali['ore']->toString(RBTime::$RBTIME_SHORT), "TB", 1, "L", 0);
		$this->SetTextColor(0);
	}

	public function pageBody($presence, $totali, $label) {
		$this->SetTextColor(0);
		$this->y_position = 40.0;
		$this->SetY($this->y_position);
		$this->pageHeader($totali, $label);
		$this->SetY($this->y_position);
		$this->SetFont('', 'B', 8);
		$this->SetX(15.0);
		$this->Cell(90, 5, "Alunno", "B", 0, "C");
		$this->Cell(22, 5, "Assenze", "B", 0, "R");
		$this->Cell(22, 5, "% Assenze", "B", 0, "R");
		$this->Cell(22, 5, "Ore assenza", "B", 0, "R");
		$this->Cell(22, 5, "% Ore assenza", "B", 0, "R");
		$this->y_position += 5.0;
		$fill = false;
		$this->SetFillColor(215, 246, 189);
		foreach ($presence as $k => $row){
			$this->SetTextColor(0);
			$perc_day = round((($row['absences'] / $totali['giorni']) * 100), 2);
			$absences = new RBTime(0, 0, 0);
			$absences->setTime($totali['ore']->getTime() - $row['presence']->getTime());
			$perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);
			if($perc_day == 0){
				$perc_day = "--";
			}
			else{
				$perc_day .= "%";
			}
			if($perc_hour == 0){
				$perc_hour = "--";
			}
			else{
				$perc_hour .= "%";
			}
					 
			$this->y_position += 7.0;
			if($this->y_position > 235){
				$this->endPage();
				$this->startPage();
				$this->pageHeader($totali, $label);
				$this->y_position = 40.0;
			}
			$this->SetY($this->y_position);
			$this->SetFont('', '', 10);
			$this->SetX(15.0);
			$this->Cell(90, 2, stripslashes($row['name']), "B", 0, "L", $fill);
			$this->Cell(22, 2, $row['absences'], "B", 0, "R", $fill);
			if($perc_day > 24.99){
				$this->SetTextColor(200,6,6);
			}
			else{
				$this->SetTextColor(0);
			}
			$this->Cell(22, 2, $perc_day, "B", 0, "R", $fill);
			$this->SetTextColor(0);
			$this->Cell(22, 2, ($absences->getTime() > 0) ? $absences->toString(RBTime::$RBTIME_SHORT) : "--", "B", 0, "R", $fill);
			if($perc_day > 24.99){
				$this->SetTextColor(200,6,6);
			}
			else{
				$this->SetTextColor(0);
			}
			$this->Cell(22, 2, $perc_hour, "B", 0, "R", $fill);
			//$fill = !$fill;
		}
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($author);
$pdf->SetTitle("Statistiche assenze classe ".$_SESSION['__classe__']->to_string());

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
$pdf->AddPage("P");

//$pdf->pageHeader($totali, $label);

$pdf->pageBody($presence, $totali, $label);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('stats.pdf', 'D');
