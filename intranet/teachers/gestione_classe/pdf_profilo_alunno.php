<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SchoolPDF.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {

	public function printProfile($student, $address, $phones) {

		$this->SetX(80.0);
		$this->SetFont('', 'B');
		$this->Write(5, $_SESSION['__current_year__']->to_string()."  -  Profilo dell'alunno  ".$student['alunno'], $align='C');

		$this->SetY(40.0);
		// Colors, line width and bold font
		$this->SetTextColor(25);
		$this->SetLineWidth(0.3);
		$this->SetFont('');
		// Data
		$this->SetFont('', 'B');
		$this->Cell(0, 5, "", 0, 1, "left");
		$this->Cell(0, 10, "Dati anagrafici", 0, 1, "left");
		$this->SetFont('');
		$this->Cell(50, 10, "Data di nascita", 0, 0, "left");
		$this->Cell(50, 10, format_date($student['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, "left");
		$this->Cell(50, 10, "Luogo di nascita", 0, 0, "left");
		$this->Cell(50, 10, $student['luogo_nascita'], 0, 1, "left");
		$this->Cell(50, 10, "Residenza", 0, 0, "left");
		$this->Cell(50, 10, $address[0]['indirizzo']." - ".$address[0]['citta'], 0, 1, "left");
		$this->SetFont('', 'B');
		$this->Cell(0, 5, "", 0, 1, "left");
		$this->Cell(0, 10, "Recapiti telefonici", 0, 1, "left");
		$this->SetFont('');
		foreach ($phones as $phone) {
			$this->Cell(50, 10, $phone['telefono'], 0, 0, "left");
			$this->Cell(150, 10, $phone['descrizione'], 0, 1, "left");
		}
	}
}

$stid = $_REQUEST['stid'];

$tel = array();
$address = array();

$sel_alunno = "SELECT CONCAT_WS(' ', cognome, nome) AS alunno, data_nascita, luogo_nascita FROM rb_alunni WHERE id_alunno = $stid";
$res_alunno = $db->execute($sel_alunno);
$alunno = $res_alunno->fetch_assoc();

$sel_add = "SELECT indirizzo, citta FROM rb_indirizzi_alunni WHERE id_alunno = $stid";
$res_add = $db->execute($sel_add);
if ($res_add->num_rows > 0) {
	while ($row = $res_add->fetch_assoc()) {
		$address[] = $row;
	}
}
else {
	$address[] = array("indirizzo" => "Non presente", "citta" => "");
}

$sel_phones = "SELECT * FROM rb_telefoni_alunni WHERE id_alunno = $stid ORDER BY principale DESC";
$res_phones = $db->execute($sel_phones);
if ($res_phones->num_rows > 0) {
	while ($row = $res_phones->fetch_assoc()) {
		$tel[$row['id']] = $row;
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($author);
$pdf->SetTitle('Profilo dell\'alunno '.$alunno['alunno']);

// set default header data
$pdf->SetHeaderData("", 0, $_SESSION['__config__']['intestazione_scuola'], $_SESSION['__config__']['indirizzo_scuola']);

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

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage("L");

// print colored table
$pdf->printProfile($alunno, $address, $tel);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('profilo_alunno.pdf', 'D');
