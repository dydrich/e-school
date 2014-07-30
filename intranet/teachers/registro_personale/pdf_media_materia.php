<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SchoolPDF.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$student_id = $_REQUEST['stid'];

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['q']))
	$q = $_REQUEST['q'];
else{
	$q = 0;
}
	
switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $student_id";
$sel_materia = "SELECT materia FROM rb_materie WHERE id_materia = ".$_SESSION['__materia__'];

try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_materia = $db->executeQuery($sel_materia);
} catch (MySQLException $ex){
	$ex->alert();
}
	
$alunno = $res_alunno->fetch_assoc();
$mt = $res_materia->fetch_assoc();
$desc_materia = $mt['materia'];

$sel_voti = "SELECT rb_voti.* FROM rb_voti WHERE rb_voti.alunno = $student_id AND materia = ".$_SESSION['__materia__']." $int_time ORDER BY data_voto ASC";
try{
	$res_voti = $db->executeQuery($sel_voti);
} catch (MySQLException $ex){
	$ex->alert();
}
$num_voti = $res_voti->num_rows;
$tot_voti = 0;
$array_voti = array();
while($row = $res_voti->fetch_assoc()){
	$tot_voti += $row['voto'];
	$data = explode("-", $row['data_voto']);
	$mese = $data[1];
	if(!isset($array_voti[$mese]))
		$array_voti[$mese] = array();
	array_push($array_voti[$mese], $row);
}

setlocale(LC_TIME, "it_IT.utf8");
$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {
	
	private $y_position = 0.0;
	private $mesi = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");
	
	function pageHeader($alunno, $materia, $num_voti, $media, $label){
		$this->SetY(25.0);
        $this->SetFont('', 'B', 9);
        //$this->SetFillColor(232, 234, 236);
        $this->SetTextColor(0);
        $this->Cell(180, 4, $_SESSION['__current_year__']->to_string()."  - Dettaglio dei voti di ".$alunno['cognome']." ".$alunno['nome']." - Materia: ".strtoupper($materia), 0, 1, "C", 0);
        $this->setCellPaddings(0, 0, 0, 3);
        $this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
        $this->Cell(180, 4, "$label - media voto: $media ($num_voti voti)", "B", 0, "C", 0);
        $this->SetTextColor(0);
	}
	
	public function pageBody($array_voti, $author, $alunno, $materia, $tot_voti, $num_voti, $quadrimestre) {
		$current_month = intval(date("m"));
		/*
		 * stringa quadrimestre per l'header e dati per la selezione dei mesi da stampare
		 */
		if($quadrimestre == 1){
			$label = "Primo quadrimestre";
			$x = 9;
			$start = 0;
			if($current_month > 8)
				$end = $current_month - 8;
			else
				$end = $current_month + 4;
		}
		else if($quadrimestre == 2){
			$x = 2;
			$label = "Secondo quadrimestre";
			$start = 5;
			$end = 10;
		}
		else{
			$label = "Riepilogo generale";
			$start = 0;
			$x = 9;
			$end = 10;
		}
		
		$months = array_slice($this->mesi, $start, $end);
		$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
		$media_voto = round(($tot_voti / $num_voti), 2);
		if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
			$media_voto = $voti_religione[$media_voto];
		}
		    	
    	$this->y_position = 40.0;
    	$this->SetY($this->y_position);
    	$this->pageHeader($alunno, $materia, $num_voti, $media_voto, $label);
    	foreach($months as $mese){
	    	if($x == 13)
				$x = 1;
			$x_str = $x;
			if(strlen($x_str) < 2){
				$x_str = "0".$x;
			}
		    if (isset($array_voti[$x_str])) {
        	    $data = $array_voti[$x_str];
		    }
		    else {
			    $data = array();
		    }
        	
        	if(count($data) > 0){
        		$this->y_position += 8.0;
	        	if($this->y_position > 265){
	       			$this->endPage();
	        		$this->startPage();
	        		$this->pageHeader($alunno, $materia, $num_voti, $media_voto, $label);
	        		$this->y_position = 40;
	        	}
	    		$this->SetY($this->y_position);
	        	$this->SetFont('', 'B', 9);
	        	$this->Cell(150, 5, "Mese di ".$mese.": ".count($data)." voti", 0, 1);
	        	$this->y_position += 2;
        		foreach($data as $row){
			        setlocale(LC_TIME, "it_IT.utf8");
        			$giorno_str = strftime("%A", strtotime($row['data_voto']));
        			$this->y_position += 4.0;
        			if($this->y_position > 265){
        				$this->endPage();
		        		$this->startPage();
		        		$this->pageHeader($alunno, $materia, $num_voti, $media_voto, $label);
		        		$this->y_position = 40;	
        			}
        			$this->SetY($this->y_position);
        			$this->SetFont('', '', 9);
        			$this->SetX(20.0);
        			$this->Cell(20, 5, ucwords($giorno_str), 0, 0);
        			$this->Cell(20, 5, format_date($row['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 0);
        			$this->Cell(20, 5, ($row['tipologia'] == 'S') ? "Scritto" : "Orale", 0, 0);
        			$this->Cell(90, 5, $row['descrizione'], 0, 0);
        			if($row['voto'] < 6)
        				$this->SetTextColor(255, 0, 0);
        			if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
        				$_voto = $voti_religione[$row['voto']];
        			}
        			else{
        				$_voto = $row['voto'];
        			}
        			$this->Cell(10, 5, $_voto, 0, 0, "R");
        			if($row['voto'] < 6)
        				$this->SetTextColor(0);
        		}
        	}
        	$x++;
        }
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($author);
$pdf->SetTitle("Elenco voti di ".$alunno['cognome']." ".$alunno['nome']);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Scuola Media Statale Arborea - Lamarmora, Iglesias", $_SESSION['__current_year__']->to_string()."  - Dettaglio delle assenze di  ".$alunno['cognome']." ".$alunno['nome']." (".mysql_num_rows($res_assenze).")");
$pdf->SetHeaderData("", 0, $_SESSION['__config__']['intestazione_scuola']);

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

$pdf->pageBody($array_voti, $author, $alunno, $desc_materia, $tot_voti, $num_voti, $q);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('voti_materia.pdf', 'D');
