<?php

require_once "../../../lib/start.php";
require_once "../../../lib/OutcomeTablePDF.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getUsername() != "rbachis") ){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: no_permission.php");
}
$anno = $_SESSION['__current_year__']->get_ID();

$sel_pubb = "SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$anno} AND quadrimestre = 2";
$res_pubb = $db->executeQuery($sel_pubb);
$row = $res_pubb->fetch_assoc();
$id_pubb = $row['id_pagella'];

$sel_data = "select rb_alunni.id_alunno, cognome, nome, rb_esiti.esito, rb_esiti.id_esito, rb_esiti.positivo FROM rb_alunni, rb_pagelle, rb_esiti WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno AND id_esito = rb_pagelle.esito AND id_pubblicazione = {$id_pubb} AND rb_alunni.id_classe = {$_SESSION['__classe__']->get_ID()} AND attivo = '1' ORDER BY cognome, nome";
try{
	$res_data = $db->executeQuery($sel_data);
} catch (MySQLException $ex){
	$ex->redirect();
}

$file = "esiti".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione().".pdf";
$pdf = new OutcomeTablePDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("IStituto comprensivo Nivola");
$pdf->SetTitle('Tabellone esiti');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
$pdf->SetFont('helvetica', '', 12);
$pdf->AddPage("P", "A4");
$pdf->createTable($res_data, $_SESSION['__classe__'], $db, $ordine_scuola);
$pdf->Output($file, 'I');