<?php

require_once "../../lib/start.php";
require_once "../../lib/SchoolPDF.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "";

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {

	public function printProfile($data) {

		$x = $_SESSION['__classe__']->getSchoolOrder();
		$school_order = 'Scuola primaria';
		if ($x == MIDDLE_SCHOOL) {
			$school_order = 'Scuola secondaria di primo grado';
		}

		//$this->SetX(40.0);
		$this->SetFont('', 'B');
		$this->Cell(0, 10, $_SESSION['__current_year__']->to_string()."  -  Elenco consiglio di classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione(), 0, 1, "C");
		//$this->Write(5, $_SESSION['__current_year__']->to_string()."  -  Elenco consiglio di classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione(), $align='C');
		$this->SetFont('', 'I', '10');
		$this->Cell(0, 5, $school_order, 0, 0, "C");
		$this->SetY(50.0);
		// Colors, line width and bold font
		$this->SetTextColor(25);
		$this->SetLineWidth(0.3);
		$this->SetFont('', '', '11');
		foreach ($data as $item) {
			$this->Cell(70, 10, $item['nome'], 0, 0, "");
			$this->Cell(110, 10, implode(', ', $item['sec_f']), 0, 1, "");
		}
	}
}

if(!isset($_REQUEST['id'])){
	$_REQUEST['id'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['id'], "__classe__");
}

$cls = $_REQUEST['id'];
$data = array();
require_once "../../lib/RBUtilities.php";
$utilities = RBUtilities::getInstance($db);
$data = $utilities->getTeachersOfClass($cls);
$class_desc = $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator($author);
$pdf->SetAuthor($author);
$pdf->SetTitle('Elenco CDC classe ');

// set default header data
$pdf->SetHeaderData("", 0, $_SESSION['__config__']['intestazione_scuola'], $_SESSION['__config__']['indirizzo_scuola']);

// set header and footer fonts
$pdf->setHeaderFont(Array('helvetica', '', 8.0));
$pdf->setFooterFont(Array('helvetica', '', 8.0));

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('dejavusans', '', 12);

// add a page
$pdf->AddPage("P");

// print colored table
$pdf->printProfile($data);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('cdc_classe.pdf', 'D');
