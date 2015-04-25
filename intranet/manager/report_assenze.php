<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 25/04/15
 * Time: 10.53
 */

require_once "../../lib/start.php";
require_once "../../lib/SchoolPDF.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$author = $_SESSION['__user__']->getFullName();

$id_alunno = $_REQUEST['s'];
$y = $_REQUEST['y'];

$desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = $y");

$assenze = array();
$assenze['09'] = array();
$assenze['10'] = array();
$assenze['11'] = array();
$assenze['12'] = array();
$assenze['01'] = array();
$assenze['02'] = array();
$assenze['03'] = array();
$assenze['04'] = array();
$assenze['05'] = array();
$assenze['06'] = array();
$sel_assenze = "SELECT data FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = $y AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NULL AND id_alunno = $id_alunno ";
$res_assenze = $db->executeQuery($sel_assenze);
$num_assenze = $res_assenze->num_rows;
while($as = $res_assenze->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	array_push($assenze[$mese], $as['data']);
}
//print_r($assenze);

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $id_alunno";
$res_alunno = $db->executeQuery($sel_alunno);
$alunno = $res_alunno->fetch_assoc();

setlocale(LC_TIME, "it_IT.utf8");

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {

	private $y_position = 0.0;

	function pageHeader($alunno, $num_assenze, $desc){
		$this->SetY(25.0);
		$this->SetFont('', 'B', 9);
		//$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->Cell(180, 4, "Anno scolastico $desc - Dettaglio delle assenze di ".$alunno['cognome']." ".$alunno['nome'], 0, 1, "C", 0);
		$this->setCellPaddings(0, 0, 0, 3);
		$this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
		$this->Cell(180, 4, "Numero assenze: ".$num_assenze, "B", 0, "C", 0);
		$this->SetTextColor(0);
	}

	public function pageBody($assenze, $author, $alunno, $num_assenze, $desc) {
		$month_code = array("09", "10", "11", "12", "01", "02", "03", "04", "05", "06");
		$mesi = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");

		$idx = 0;
		$this->y_position = 30.0;
		$this->SetY($this->y_position);
		$this->pageHeader($alunno, $num_assenze, $desc);
		foreach($month_code as $code){
			$data = $assenze[$code];

			if(count($data) > 0){
				$this->y_position += 8.0;
				if($this->y_position > 265){
					$this->endPage();
					$this->startPage();
					$this->pageHeader($alunno, $num_assenze, $desc);
					$this->y_position = 40;
				}
				$this->SetY($this->y_position);
				$this->SetFont('', 'B', 9);
				$this->Cell(150, 5, "Mese di ".$mesi[$idx].": ".count($data)." assenze", 0, 1);
				foreach($data as $day){
					$giorno_str = strftime("%A", strtotime($day));
					$this->y_position += 4.0;
					if($this->y_position > 265){
						$this->endPage();
						$this->startPage();
						$this->pageHeader($alunno, $num_assenze, $desc);
						$this->y_position = 40;
					}
					$this->SetY($this->y_position);
					$this->SetFont('', '', 9);
					$this->SetX(30.0);
					$this->Cell(20, 5, $giorno_str, 0, 0);
					$this->Cell(20, 5, format_date($day, SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 0);
				}
			}
			$idx++;
		}
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($author);
$pdf->SetTitle("Elenco assenze di ".$alunno['cognome']." ".$alunno['nome']);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Scuola Media Statale Arborea - Lamarmora, Iglesias", $_SESSION['__current_year__']->to_string()."  - Dettaglio delle assenze di  ".$alunno['cognome']." ".$alunno['nome']." (".mysql_num_rows($res_assenze).")");
$pdf->SetHeaderData("", 0, $_SESSION['__config__']['intestazione_scuola']." - secondaria di primo grado", $_SESSION['__config__']['indirizzo_scuola']);

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

$pdf->pageBody($assenze, $author, $alunno, $num_assenze, $desc);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('report_assenze.pdf', 'D');
