<?php

require_once "../../../lib/start.php";
require_once "../../../lib/ClassbookData.php";

check_session();
check_permission(DOC_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

if((($_SESSION['__user__']->getSchoolOrder() == 1 && !$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()))) && (!$_SESSION['__user__']->isAdministrator()) && ($_SESSION['__user__']->getUsername() != "rbachis") ){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: ../no_permission.php");
}

$q = null;
if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$_tday = date("Y-m-d");
switch($q){
	case 0:
		$par_tot = "AND data <= NOW()";
		break;
	case 1:
		$fq = $fine_q;
		$min = $_tday < $fq ? $_tday : $fq;
		$par_tot = "AND DATA <= '{$min}'";
		break;
	case 2:
		$par_tot = "AND (data > '".$fine_q."' AND data <= NOW()) ";
}

$module = $_SESSION['__classe__']->get_modulo_orario();
$classbook_data = new ClassbookData($_SESSION['__classe__'], $school_year, $par_tot, $db);
$totali = $classbook_data->getClassSummary();
$presence = $classbook_data->getStudentsSummary();
$navigation_label = "Registro della classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = $_SESSION['__classe__']->to_string()." - Statistiche di presenza ";
if($q == 1) {
	$drawer_label .= "primo quadrimestre";
}
else if($q == 2) {
	$drawer_label .= "secondo quadrimestre";
}
else {
	$drawer_label .= "totale anno scolastico";
}

include "stats.html.php";
