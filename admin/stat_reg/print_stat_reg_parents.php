<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 03/03/14
 * Time: 12.37
 */

require_once "../../lib/start.php";
require_once "../../lib/SchoolPDF.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area_label__'] = "Area amministrazione";

$classes_table = "rb_classi";
$ordine_scuola = "";
if (isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$ordine_scuola = $_GET['school_order'];
}
else if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$ordine_scuola = $_SESSION['__school_order__'];
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
	$ordine_scuola = $_SESSION['school_order'];
}

$sel_cls = "SELECT id_classe, anno_corso, sezione, $classes_table.ordine_di_scuola, rb_sedi.nome FROM {$classes_table}, rb_sedi, rb_tipologia_scuola WHERE sede = rb_sedi.id_sede AND {$classes_table}.ordine_di_scuola = id_tipo AND rb_tipologia_scuola.attivo = 1 ORDER BY sezione, anno_corso, sede ";
$res_cls = $db->execute($sel_cls);
$classi = array();
while ($row = $res_cls->fetch_assoc()){
	$classi[$row['id_classe']] = array();
	$classi[$row['id_classe']]['cls'] = $row;
	$classi[$row['id_classe']]['alunni'] = array();
}

$sel_alunni = "SELECT rb_alunni.*, anno_corso, sezione FROM rb_alunni, rb_classi WHERE rb_classi.id_classe = rb_alunni.id_classe AND attivo = '1' AND rb_alunni.id_alunno NOT IN (SELECT rb_genitori_figli.id_alunno FROM rb_genitori_figli) ORDER BY sezione, anno_corso, rb_alunni.cognome, rb_alunni.nome";
//print $sel_alunni;
$res_alunni = $db->execute($sel_alunni);
$num_alunni = $res_alunni->num_rows;
while($alunno = $res_alunni->fetch_assoc()){
	if (isset($classi[$alunno['id_classe']])){
		$classi[$alunno['id_classe']]['alunni'][] = $alunno;
	}
	else {
		$num_alunni--;
	}
}

setlocale(LC_ALL, "it_IT");

$author = $_SESSION['__user__']->getFullName();

class MYPDF extends SchoolPDF {

	private $y_position = 0.0;

	function pageHeader($num_alunni, $school_order){
		$this->SetY(25.0);
		$this->SetFont('', 'B', 9);
		//$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->Cell(180, 4, $_SESSION['__current_year__']->to_string()."  - Genitori non registrati - {$school_order}: estratti {$num_alunni} alunni", 0, 1, "C", 0);
		$this->setCellPaddings(0, 0, 0, 3);
		$this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
		$this->SetTextColor(0);
	}

	public function pageBody($classi, $num_alunni, $school_order) {
		$this->SetTextColor(0);
		$this->SetY(30);
		$this->SetFont('', 'B', 8);
		$this->SetX(15.0);
		$this->y_position += 5.0;
		$fill = false;
		$this->SetFillColor(215, 246, 189);
		foreach ($classi as $cls){
			$this->SetTextColor(0);
			$this->SetFont('', 'B', 9);
			$this->SetX(15.0);
			$this->Cell(0, 4, '', 0, 1);
			$this->Cell(0, 5, "Classe ".$cls['cls']['anno_corso'].$cls['cls']['sezione']." ".$cls['cls']['nome']." (".count($cls['alunni'])." )", array('B' => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))), 0, "L", $fill);
			$this->Ln();
			$this->SetFont('', '', 8);
			foreach ($cls['alunni'] as $alunno){
				$this->Cell(0, 0, $alunno['cognome']." ".$alunno['nome'], "", 0, "L", 0);
				$this->Ln();

			}
		}
	}
}

$school_order = "scuola secondaria di primo grado";
$pdf_label = "secondaria";
if ($ordine_scuola == 2){
	$school_order = "scuola primaria";
	$pdf_label = "primaria";
}

// create new PDF document
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator($author);
$pdf->SetAuthor($author);
$pdf->SetTitle("Genitori non registrati al Registro elettronico - {$school_order}");

$pdf->SetHeaderData("", 0, $_SESSION['__config__']['intestazione_scuola']." - ".$school_order, $_SESSION['__config__']['indirizzo_scuola']);

// set header and footer fonts
$pdf->setHeaderFont(Array('helvetica', '', 8.0));
$pdf->setFooterFont(Array('helvetica', '', 8.0));

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

$pdf->pageHeader($num_alunni, $school_order);

$pdf->pageBody($classi, $num_alunni, $school_order);

// ---------------------------------------------------------

//Close and output PDF document

$pdf->Output('parents_stats_'.$pdf_label.'.pdf', 'D');
