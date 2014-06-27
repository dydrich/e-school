<?php

ini_set("display_errors", DISPLAY_ERRORS);

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
		$par_tot = "AND (data BETWEEN '".$fine_q."' AND NOW()) ";
}

$uscite = array();
$mesi = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");

$sel_uscite = "SELECT data, rb_reg_alunni.uscita AS uscita FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND rb_reg_alunni.uscita < rb_reg_classi.uscita AND id_alunno = $id_alunno ";
$res_uscite = $db->executeQuery($sel_uscite);
$num_uscite = $res_uscite->num_rows;
while($as = $res_uscite->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	if(!isset($uscite[$mese]))
		$uscite[$mese] = array();
	array_push($uscite[$mese], $as);
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $id_alunno";
$res_alunno = $db->executeQuery($sel_alunno);
$alunno = $res_alunno->fetch_assoc();

$sel_somma_uscite  = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(rb_reg_classi.uscita, rb_reg_alunni.uscita))))) AS ore_perse, COUNT(rb_reg_alunni.uscita) AS giorni_anticipo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND id_alunno = $id_alunno AND rb_reg_classi.uscita > rb_reg_alunni.uscita ";
$res_somma_uscite = $db->executeQuery($sel_somma_uscite);
$somma_uscite = $res_somma_uscite->fetch_assoc();

setlocale(LC_ALL, "it_IT");

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {
	
	private $y_position = 0.0;
	
	function pageHeader($alunno, $num_uscite, $tot_uscite){
		$this->SetY(25.0);
        $this->SetFont('', 'B', 9);
        //$this->SetFillColor(232, 234, 236);
        $this->SetTextColor(0);
        $this->Cell(180, 4, $_SESSION['__current_year__']->to_string()."  - Dettaglio delle uscite anticipate di  ".$alunno['cognome']." ".$alunno['nome'], 0, 1, "C", 0);
        $this->setCellPaddings(0, 0, 0, 3);
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
        $this->Cell(90, 4, "Numero uscite anticipate: ".$num_uscite, "B", 0, "R", 0);
        $this->Cell(90, 4, "     Totale ore: ".substr($tot_uscite['ore_perse'], 0, 5), "B", 1, "L", 0);
        $this->SetTextColor(0);
	}
	
	public function pageBody($uscite, $author, $alunno, $mesi, $num_uscite, $somma_uscite) {
    	$x = 9;
		$this->y_position = 30.0;
    	$this->SetY($this->y_position);
    	$this->pageHeader($alunno, $num_uscite, $somma_uscite);
    	foreach($mesi as $mese){
	    	if($x == 13)
				$x = 1;
			$x_str = $x;
			if(strlen($x_str) < 2){
				$x_str = "0".$x;
			}
        	$data = $uscite[$x_str];
        	
        	if(count($data) > 0){
        		$this->y_position += 8.0;
	        	if($this->y_position > 265){
	       			$this->endPage();
	        		$this->startPage();
	        		$this->pageHeader($alunno, $num_uscite, $somma_uscite);
	        		$this->y_position = 40.0;
	        	}
	    		$this->SetY($this->y_position);
	        	$this->SetFont('', 'B', 9);
	        	$this->Cell(150, 5, "Mese di ".$mese.": ".count($data)." uscite anticipate", 0, 1);
        		foreach($data as $day){
					$giorno_str = strftime("%A", strtotime($day['data']));
        			$this->y_position += 4.0;
        			if($this->y_position > 265){
        				$this->endPage();
		        		$this->startPage();
		        		$this->pageHeader($alunno, $num_uscite, $somma_uscite);
	        		$this->y_position = 40.0;
        			}
        			$this->SetY($this->y_position);
        			$this->SetFont('', '', 9);
        			$this->SetX(30.0);
        			$this->Cell(20, 5, utf8_encode($giorno_str), 0, 0);
        			$this->Cell(20, 5, format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 0);
        			$this->Cell(20, 5, " ore ".substr($day['uscita'], 0, 5), 0, 0);
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
$pdf->SetTitle("Elenco uscite anticipate di ".$alunno['cognome']." ".$alunno['nome']);

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

$pdf->pageBody($uscite, $author, $alunno, $mesi, $num_uscite, $somma_uscite);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('uscite_anticipate.pdf', 'D');

?>