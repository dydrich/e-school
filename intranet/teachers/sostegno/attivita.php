<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if(!isset($_REQUEST['cls'])){
	$_REQUEST['cls'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}

$idd = 0;
if (isset($_SESSION['__sp_student__']['dati']['id'])){
	$idd = $_SESSION['__sp_student__']['dati']['id'];
}

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$today = date("Y-m-d");
if($today > $fine_lezioni){
	$today = $fine_lezioni;
}

/*
 * date lezioni
*/
$param = "";
$c = intval(date("m"));
$month = "current";
if (isset($_GET['m'])) {
	$month = $_GET['m'];
}
if ($month == "current"){
	if ($c == 7 || $c == 8){
		$month = 6;
	}
	else{
		$month = $c;
	}
}
if (isset($_GET['m'])){
	$param = "AND DATE_FORMAT(data, '%c') = {$month}";
}

$previous = $month - 1;
$next = $month + 1;
if ($next > 12) $next = 1;
if ($previous < 1) $previous = 12;
if ($month == 6) $next = null;
if ($month == 9) $previous = null;
$start = 0;

$mesi_scuola = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");
$num_mesi_scuola = array(9, 10, 11, 12, 1, 2, 3, 4, 5, 6);
$mesi = array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre", );
/*
 * barra di navigazione tra i mesi
*/
$min_m = 0;
if ($c > 5 && $c < 9){
	$max_m = 9;
}
else if ($c > 8){
	$max_m = $c - 9;
}
else {
	$max_m = $c + 3;
}

$sel_attivita = "SELECT * FROM rb_attivita_sostegno WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND alunno = {$_SESSION['__sp_student__']['alunno']} {$param} ORDER BY data DESC";
$res_attivita = $db->execute($sel_attivita);
//echo $sel_attivita;

$navigation_label = "registro del sostegno ";
$drawer_label = "Attivit&agrave; svolte - ". $_SESSION['__sp_student__']['cognome']." ".$_SESSION['__sp_student__']['nome'];

include "attivita.html.php";
