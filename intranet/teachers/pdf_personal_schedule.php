<?php

require_once "../../lib/start.php";
require_once "../../lib/SchoolPDF.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$schedule = $_SESSION['personal_schedule'];
$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {
	
	public $ore = 8;

	// array contenente l'orario iniziale delle ore di lezione
	public $inizio_ore = array("", "8:30", "9:30", "10:30", "11:30", "12:30", "14:30", "15:30", "16:30");

    public function ColoredTable($header,$data, $classi) {
    	
    	$this->SetX(80.0);
    	$this->SetFont('', 'B');
    	$this->Write(5, $_SESSION['__current_year__']->to_string()."  -  Orario delle lezioni di ".$_SESSION['__user__']->getFullName(), $align='C');
    	
    	$this->SetY(40.0);
        // Colors, line width and bold font
        $this->SetFillColor(131, 2, 29);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(26, 80, 80, 80);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(232, 234, 236);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        for($i = 0; $i < $this->ore; $i++){
        	reset($data);
        	if(isset($data[1][$i + 1])) {
        		$d1 = $data[1][$i + 1]['cl'].$data[1][$i + 1]['sezione']." -- ".substr($data['1'][$i + 1]['mat'], 0, 19); 
        		if($data['1'][$i + 1]['descrizione'] != "")
        			$d1 .= " (".$data['1'][$i + 1]['descrizione'].")";
        	} 
        	else{
        		$d1 = "--";
        	}
        	//print $d1;
        	if(isset($data['2'][$i + 1])) {
        		$d2 = $data['2'][$i + 1]['cl'].$data['2'][$i + 1]['sezione']." -- ".substr($data['2'][$i + 1]['mat'], 0, 19); 
        		if($data['2'][$i + 1]['descrizione'] != "")
        			$d2 .= " (".$data['2'][$i + 1]['descrizione'].")";
        	} 
        	else{
        		$d2 = "--";
        	} 
        	if(isset($data['3'][$i + 1])) {
        		$d3 = $data['3'][$i + 1]['cl'].$data['3'][$i + 1]['sezione']." -- ".substr($data['3'][$i + 1]['mat'], 0, 19); 
        		if($data['3'][$i + 1]['descrizione'] != "")
        			$d3 .= " (".$data['3'][$i + 1]['descrizione'].")";
        	} 
        	else{
        		$d3 = "--";
        	} 
        	$this->Cell($w[0], 6, $this->inizio_ore[$i + 1], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $d1, 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, $d2, 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $d3, 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
        
        $this->SetY(110.0);
        // Colors, line width and bold font
        $this->SetFillColor(131, 2, 29);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(26, 80, 80, 80);
        $header2 = array('Ora', 'Gio', 'Ven', 'Sab');
        $num_headers = count($header2);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header2[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(232, 234, 236);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
    	for($i = 0; $i < $this->ore; $i++){
        	reset($data);
        	if(isset($data['4'][$i + 1])) {
        		$d1 = $data['4'][$i + 1]['cl'].$data['4'][$i + 1]['sezione']." -- ".substr($data['4'][$i + 1]['mat'], 0, 19); 
        		if($data['4'][$i + 1]['descrizione'] != "")
        			$d1 .= " (".$data['4'][$i + 1]['descrizione'].")";
        	} 
        	else{
        		$d1 = "--";
        	}
        	//print $d1;
        	if(isset($data['5'][$i + 1])) {
        		$d2 = $data['5'][$i + 1]['cl'].$data['5'][$i + 1]['sezione']." -- ".substr($data['5'][$i + 1]['mat'], 0, 19); 
        		if($data['5'][$i + 1]['descrizione'] != "")
        			$d2 .= " (".$data['5'][$i + 1]['descrizione'].")";
        	} 
        	else{
        		$d2 = "--";
        	} 
        	if(isset($data['6'][$i + 1])) {
        		$d3 = $data['6'][$i + 1]['cl'].$data['6'][$i + 1]['sezione']." -- ".substr($data['6'][$i + 1]['mat'], 0, 19); 
        		if($data['6'][$i + 1]['descrizione'] != "")
        			$d3 .= " (".$data['6'][$i + 1]['descrizione'].")";
        	} 
        	else{
        		$d3 = "--";
        	} 
        	$this->Cell($w[0], 6, $this->inizio_ore[$i + 1], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $d1, 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, $d2, 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $d3, 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($author);
$pdf->SetTitle('Orario personale');

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

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage("L");

//Column titles
$header = array('Ora', 'Lun', 'Mar', 'Mer');

// print colored table
$pdf->ColoredTable($header, $schedule, "");

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('orario.pdf', 'D');

?>