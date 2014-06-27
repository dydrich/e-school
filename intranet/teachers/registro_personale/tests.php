<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";

ini_set("display_errors", DISPLAY_ERRORS);

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

if(isset($_REQUEST['q']))
	$q = $_REQUEST['q'];
else{
	if(date("Y-m-d") > $fine_q){
		$q = 2;
	}
	else{
		$q = 1;
	}
}

switch($q){
	case 0:
		$int_time = "AND data_verifica <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND DATE(data_verifica) <= '".$fine_q."'";
		$label = " primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (DATE(data_verifica) > '".$fine_q."' AND data_verifica <= NOW()) ";
		$label = " secondo quadrimestre";
}

$sel_tests = "SELECT * FROM rb_verifiche WHERE id_docente = ".$_SESSION['__user__']->getUid()." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_classe = ".$_SESSION['__classe__']->get_ID();
if(isset($_REQUEST['subj'])){
	$subj = $_REQUEST['subj'];
	$_SESSION['__materia__'] = $_REQUEST['subj'];
}
else
	$subj = $_SESSION['__materia__'];
$sel_tests .= " AND id_materia = $subj $int_time ORDER BY valutata, data_verifica DESC";
$res_tests = $db->execute($sel_tests);
//print $sel_tests;

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

$mat = $_SESSION['__user__']->getSubject();

$change_subject = new ChangeSubject("hid", "", "position: absolute; width: 180px; height: 55px; display: none", "div", $_SESSION['__subjects__']);
$change_subject->createLink("text-decoration: none; text-transform: uppercase; font-weight: bold", "right");

$navigation_label = "Registro personale del docente - Classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

setlocale(LC_ALL, "it_IT");

include "tests.html.php";

?>