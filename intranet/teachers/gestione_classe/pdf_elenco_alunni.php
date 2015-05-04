<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SchoolPDF.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {

	public function printProfile($students, $list_type) {

		$this->SetX(40.0);
		$this->SetFont('', 'B');
		$this->Write(5, $_SESSION['__current_year__']->to_string()."  -  Elenco alunni classe  ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione(), $align='C');

		$this->SetY(40.0);
		// Colors, line width and bold font
		$this->SetTextColor(25);
		$this->SetLineWidth(0.3);
		$this->SetFont('');

		// Data
		switch ($list_type) {
			case 1:
				foreach ($students as $student) {
					$this->Cell(150, 10, $student['anagrafica']['alunno'], 0, 1, "left");
				}
				break;
			case 2:
				$this->SetFont('', '', '11');
				foreach ($students as $student) {
					$vowel = "o";
					if ($student['anagrafica']['sesso'] == "F") {
						$vowel = "a";
					}
					$this->Cell(100, 10, $student['anagrafica']['alunno'], array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 0, "left");
					$this->Cell(100, 10, "Nat".$vowel." il ".format_date($student['anagrafica']['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")." a ".$student['anagrafica']['luogo_nascita'], array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "left");
				}
				break;
			case 3:
				$this->SetFont('', '', '11');
				foreach ($students as $student) {
					$this->Cell(70, 10, $student['anagrafica']['alunno'], array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 0, "left");
					$this->Cell(130, 10, $student['indirizzo']['indirizzo']." - ".$student['indirizzo']['citta'], array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "left");
				}
				break;
			case 4:
				$this->SetFont('', '', '11');
				foreach ($students as $student) {
					$tel = "";
					if (count($student['telefoni']) > 0) {
						foreach ($student['telefoni'] as $telefono) {
							$tel .= $telefono['telefono'].", ";
						}
					}
					$tel = substr($tel, 0, strlen($tel) - 2);
					$this->Cell(70, 10, $student['anagrafica']['alunno'], array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 0, "left");
					$this->Cell(130, 10, $tel, array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "left");
				}
				break;
			case 5:
				$this->SetFont('', '', '11');
				foreach ($students as $student) {
					$this->Cell(70, 7, $student['anagrafica']['alunno'], 0, 1, "left");
					$this->SetFont('', '', '9');

					foreach ($student['telefoni'] as $phone) {
						$this->SetX(20.0);
						$this->Cell(70, 5, $phone['telefono']." - ".$phone['descrizione'], 0, 1, "left");
					}
					$this->Cell(0, 1, "", array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "left");
					$this->Cell(70, 3, "", 0, 1, "left");
				}
				break;
			case 6:
				foreach ($students as $student) {
					$this->SetFont('', 'B', '10');
					$vowel = "o";
					if ($student['anagrafica']['sesso'] == "F") {
						$vowel = "a";
					}
					$this->Cell(70, 7, $student['anagrafica']['alunno'], 0, 1, "left");
					$this->SetFont('', '', '8');
					$this->Cell(130, 5, "Nat".$vowel." il ".format_date($student['anagrafica']['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")." a ".$student['anagrafica']['luogo_nascita'], 0, 1, "left");
					$this->Cell(0, 5, "Residente in ".$student['indirizzo']['indirizzo']." - ".$student['indirizzo']['citta'], 0, 1, "left");
					$this->Cell(0, 5, "Recapiti telefonici", 0, 1, "left");
					$this->SetFont('', '', '8');
					foreach ($student['telefoni'] as $phone) {
						$this->SetX(20.0);
						$this->Cell(70, 4, $phone['telefono']." - ".$phone['descrizione'], 0, 1, "left");
					}
					$this->Cell(0, 1, "", array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "left");
					$this->Cell(70, 3, "", 0, 1, "left");
				}
				break;
		}


	}
}

$students = array();

$sel_alunni = "SELECT CONCAT_WS(' ', cognome, nome) AS alunno, data_nascita, luogo_nascita, id_alunno, sesso FROM rb_alunni WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND attivo = 1 ORDER BY cognome, nome ";
$res_alunni = $db->execute($sel_alunni);
while ($alunno = $res_alunni->fetch_assoc()) {
	$students[$alunno['id_alunno']]['anagrafica'] = $alunno;
	$students[$alunno['id_alunno']]['indirizzo'] = array("indirizzo" => "Non presente", "citta" => "");
	$students[$alunno['id_alunno']]['telefoni'] = array();
}

$sel_add = "SELECT indirizzo, citta, rb_alunni.id_alunno FROM rb_indirizzi_alunni, rb_alunni WHERE rb_indirizzi_alunni.id_alunno = rb_alunni.id_alunno AND attivo = 1 AND id_classe = ".$_SESSION['__classe__']->get_ID();
$res_add = $db->execute($sel_add);
if ($res_add->num_rows > 0) {
	while ($row = $res_add->fetch_assoc()) {
		$students[$row['id_alunno']]['indirizzo']['indirizzo'] = $row['indirizzo'];
		$students[$row['id_alunno']]['indirizzo']['citta'] = $row['citta'];
	}
}

$sel_phones = "SELECT telefono, descrizione, rb_alunni.id_alunno FROM rb_telefoni_alunni, rb_alunni WHERE rb_telefoni_alunni.id_alunno = rb_alunni.id_alunno AND attivo = 1 AND id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY principale DESC";
$res_phones = $db->execute($sel_phones);
if ($res_phones->num_rows > 0) {
	while ($row = $res_phones->fetch_assoc()) {
		$students[$row['id_alunno']]['telefoni'][] = array("telefono" => $row['telefono'], "descrizione" => $row['descrizione']);
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($author);
$pdf->SetTitle('Elenco alunni');

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
$pdf->AddPage("P");

// print colored table
$pdf->printProfile($students, $_REQUEST['t']);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('elenco_alunni.pdf', 'D');
