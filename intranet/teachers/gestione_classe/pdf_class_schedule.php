<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SchoolPDF.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$classe = $_SESSION['__classe__']->get_ID();
$anno = $_SESSION['__current_year__']->get_ID();

$orario_classe = new Orario();
$sel_orario = "SELECT * FROM rb_orario WHERE classe = ".$_SESSION['__classe__']->get_ID()." AND anno = $anno ORDER BY giorno, ora";
//print $sel_orario;
$res_orario = $db->execute($sel_orario);
while($ora = $res_orario->fetch_assoc()){
	$a = new OraDiLezione($ora);
	$orario_classe->addHour($a);
	//print $a->getClasse();
}

$sel_cdc = "SELECT id_docente, rb_cdc.id_materia, idpadre FROM rb_cdc, rb_materie WHERE id_classe = $classe AND id_anno = $anno AND rb_cdc.id_materia = rb_materie.id_materia AND id_docente IS NOT NULL ";
$res_cdc = $db->execute($sel_cdc);
$consiglio = array();
while($con = $res_cdc->fetch_assoc()){
    $consiglio[$con['id_materia']] = $con['id_docente'];
}

$materie = array();
$sel_materie = "SELECT * FROM rb_materie WHERE (idpadre <> 13 AND idpadre <> 7) OR idpadre is null";
$res_materie = $db->execute($sel_materie);
while($mat = $res_materie->fetch_assoc()){
	//print "<br /><br />New subject<br />";
	$id_doc = 0;
	reset($consiglio);
	while(list($k, $v) = each($consiglio)){
		//print "Confronto k=$k con id_materia=".$mat['id_materia']." e idpadre=".$mat['idpadre']."<br />";
		if(($mat['id_materia'] == $k) || ($mat['idpadre'] == $k)){
			$id_doc = $v;
			break;
		}
	}
	$materie[$mat['id_materia']] = array($mat['materia'], $mat['idpadre'], $id_doc);
}

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {
	
	public $ore = 8;

	// array contenente l'orario iniziale delle ore di lezione
	public $inizio_ore = array("", "8:30", "9:30", "10:30", "11:30", "12:30", "14:30", "15:30", "16:30");

    public function ColoredTable($header,$orario_classe, $materie, $classe, $full_time) {
    	if(!$full_time)
    		$this->ore = 5;
    	
    	$this->SetX(80.0);
    	$this->SetFont('', 'B');
    	$this->Write(5, $_SESSION['__current_year__']->to_string()."  -  Orario delle lezioni della classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione(), $align='C');
    	
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
        	reset($materie);
        	
        	$this->Cell($w[0], 6, $this->inizio_ore[$i + 1], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, ($materie[$orario_classe->getMateria($classe, "1", $i+1)][0] != "Scegli") ? $materie[$orario_classe->getMateria($classe, "1", $i+1)][0] : "--", 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, ($materie[$orario_classe->getMateria($classe, "2", $i+1)][0] != "Scegli") ? $materie[$orario_classe->getMateria($classe, "2", $i+1)][0] : "--", 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, ($materie[$orario_classe->getMateria($classe, "3", $i+1)][0] != "Scegli") ? $materie[$orario_classe->getMateria($classe, "3", $i+1)][0] : "--", 'LR', 0, 'C', $fill);
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
        	reset($materie);
        	$this->Cell($w[0], 6, $this->inizio_ore[$i + 1], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, ($materie[$orario_classe->getMateria($classe, "4", $i+1)][0] != "Scegli") ? $materie[$orario_classe->getMateria($classe, "4", $i+1)][0] : "--", 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, ($materie[$orario_classe->getMateria($classe, "5", $i+1)][0] != "Scegli") ? $materie[$orario_classe->getMateria($classe, "5", $i+1)][0] : "--", 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, ($materie[$orario_classe->getMateria($classe, "6", $i+1)][0] != "Scegli") ? $materie[$orario_classe->getMateria($classe, "6", $i+1)][0] : "--", 'LR', 0, 'C', $fill);
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
$pdf->SetTitle($_SESSION['__classe__']->to_string());

// set default header data
$pdf->SetHeaderData("", 0, $_SESSION['__config__']['intestazione_scuola'], $_SESSION['__config__']['indirizzo_scuola']);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 9.0));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 9.0));

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
$pdf->ColoredTable($header, $orario_classe, $materie, $classe, $_SESSION['__classe__']->isFullTime());

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('orario.pdf', 'D');

?>
