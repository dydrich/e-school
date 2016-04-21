<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SchoolPDF.php";

check_session();
check_permission(DOC_PERM|GEN_PERM|STD_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$id_alunno = $_REQUEST['alunno'];
$quadrimestre = $_REQUEST['q'];

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

switch($quadrimestre){
	case 0:
		$par_tot = "AND data <= NOW()";
		break;
	case 1:
		$par_tot = "AND DATA <= '".$fine_q."'";
		break;
	case 2:
		$par_tot = "AND (data > '".$fine_q."' AND data <= NOW()) ";
}

$ritardi = array();
$mesi = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");

$sel_ritardi = "SELECT data, rb_reg_alunni.ingresso AS ingresso FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso AND id_alunno = $id_alunno ";
$res_ritardi = $db->executeQuery($sel_ritardi);
$num_ritardi = $res_ritardi->num_rows;
while($as = $res_ritardi->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	if(!isset($ritardi[$mese]))
		$ritardi[$mese] = array();
	array_push($ritardi[$mese], $as);
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $id_alunno";
$res_alunno = $db->executeQuery($sel_alunno);
$alunno = $res_alunno->fetch_assoc();

$sel_somma_ritardi = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(rb_reg_alunni.ingresso, rb_reg_classi.ingresso))))) AS ore_ritardo, COUNT(rb_reg_alunni.ingresso) AS giorni_ritardo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND id_alunno = $id_alunno AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso ";
$res_somma_ritardi = $db->executeQuery($sel_somma_ritardi);
$somma_ritardi = $res_somma_ritardi->fetch_assoc();

setlocale(LC_ALL, "it_IT");

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {
	
	private $y_position = 0.0;
	
	function pageHeader($alunno, $num_ritardi, $tot_ritardi){
		$this->SetY(25.0);
        $this->SetFont('', 'B', 9);
        //$this->SetFillColor(232, 234, 236);
        $this->SetTextColor(0);
        $this->Cell(180, 4, $_SESSION['__current_year__']->to_string()."  - Dettaglio dei ritardi di  ".$alunno['cognome']." ".$alunno['nome'], 0, 1, "C", 0);
        $this->setCellPaddings(0, 0, 0, 3);
        $this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
        $this->Cell(90, 4, "Numero ritardi: ".$num_ritardi, "B", 0, "R", 0);
        $this->Cell(90, 4, "     Totale ore: ".substr($tot_ritardi['ore_ritardo'], 0, 5), "B", 1, "L", 0);
        $this->SetTextColor(0);
	}
	
	public function pageBody($ritardi, $author, $alunno, $mesi, $num_ritardi, $somma_ritardi) {
		$this->SetTextColor(0);
	   	$x = 9;
		$this->y_position = 30.0;
    	$this->SetY($this->y_position);
    	$this->pageHeader($alunno, $num_ritardi, $somma_ritardi);
    	foreach($mesi as $mese){
	    	if($x == 13)
				$x = 1;
			$x_str = $x;
			if(strlen($x_str) < 2){
				$x_str = "0".$x;
			}
        	$data = $ritardi[$x_str];
        	
        	if(count($data) > 0){
        		$this->y_position += 8.0;
	        	if($this->y_position > 235){
	       			$this->endPage();
	        		$this->startPage();
	        		$this->pageHeader($alunno, $num_ritardi, $somma_ritardi);
	        		$this->y_position = 40.0;
	        	}
	    		$this->SetY($this->y_position);
	        	$this->SetFont('', 'B', 9);
	        	$this->Cell(50, 5, "Mese di ".$mese.": ".count($data)." ritardi", 0, 1);
        		foreach($data as $day){
					$giorno_str = strftime("%A", strtotime($day['data']));
        			$this->y_position += 4.0;
        			if($this->y_position > 235){
        				$this->endPage();
		        		$this->startPage();
		        		$this->y_position = 40;
		        		$this->pageHeader($alunno, $num_ritardi, $somma_ritardi);
		        			
        			}
        			$this->SetY($this->y_position);
        			$this->SetFont('', '', 9);
        			$this->SetX(30.0);
        			$this->Cell(20, 5, $giorno_str, 0, 0);
        			$this->Cell(20, 5, format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 0);
        			$this->Cell(20, 5, " ore ".substr($day['ingresso'], 0, 5), 0, 0);
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
$pdf->SetTitle("Elenco ritardi di ".$alunno['cognome']." ".$alunno['nome']);

// set default header data
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

$pdf->pageBody($ritardi, $author, $alunno, $mesi, $num_ritardi, $somma_ritardi);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('ritardi.pdf', 'D');
