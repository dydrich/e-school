<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

require_once "../reload_class_in_session.php";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['q'])) {
	$q = $_REQUEST['q'];
}
else {
	if(date("Y-m-d") > $fine_q) {
		$q = 2;
	}
	else {
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

if(isset($_REQUEST['subj'])){
	$subj = $_REQUEST['subj'];
	$_SESSION['__materia__'] = $_REQUEST['subj'];
}
else {
	$subj = $_SESSION['__materia__'];
}
$sel_tests = "SELECT * FROM rb_verifiche WHERE id_materia = $subj AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_classe = ".$_SESSION['__classe__']->get_ID()." $int_time ORDER BY valutata, data_verifica DESC";
$res_tests = $db->execute($sel_tests);
//print $sel_tests;

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

$mat = $_SESSION['__user__']->getSubject();

$change_subject = new ChangeSubject("hid", "", "position: absolute; width: 180px; height: 55px; display: none", "div", $_SESSION['__subjects__']);
$change_subject->createLink("text-decoration: none; text-transform: uppercase; font-weight: bold", "right");
$change_subject->setJavascript("", "jquery");

$navigation_label = "registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Elenco verifiche ".$label;

setlocale(LC_TIME, "it_IT.utf8");

include "tests.html.php";
