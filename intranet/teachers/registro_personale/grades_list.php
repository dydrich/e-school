<?php

require_once "../../../lib/start.php";

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

$year = isset($_REQUEST['year']) ? $_REQUEST['year'] : $_SESSION['__current_year__']->get_ID();
$q = $_REQUEST['q'];
$field_order = isset($_REQUEST['field_order']) ? $_REQUEST['field_order'] : "data_voto";
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : " DESC";
$student = isset($_REQUEST['stid']) ? $_REQUEST['stid'] : $_SESSION['__user__']->getUid();
$teacher = isset($_REQUEST['teacher']) ? $_REQUEST['teacher'] : $_SESSION['__user__']->getUid();
$months = array("09", "10", "11", "12", "01", "02", "03", "04", "05");
$italian_months = array("settembre", "ottobre", "novembre", "dicembre", "gennaio", "febbraio", "marzo", "aprile", "maggio");

if(!isset($_REQUEST['group']) || $_REQUEST['group'] == "0"){
	$group = false;
	$link_label = "Raggruppa per mese";
	$link_label2 = "Raggruppa per materia";
	$_group = "&group=1";
	$_group2 = "&group=2";
}
else{
	$group = true;
	$link_label = "Non raggruppare";
	$_group = "";
}

switch($q){
	case 0:
		$int_time = "AND data_voto < NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto  > '".$fine_q."' AND data_voto <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$image = ($order == "DESC") ? "up.png" : "down.png";
$order_to = ($order == "DESC") ? "ASC" : "DESC";

$sel_al = "SELECT CONCAT_WS(' ', cognome, nome) AS fn FROM rb_alunni WHERE id_alunno = $student";
$fn = $db->executeCount($sel_al);

$sel_voti = "SELECT rb_voti.materia, voto, modificatori, descrizione, tipologia, data_voto, rb_materie.materia AS mat FROM rb_voti, rb_materie ";
$sel_voti .= "WHERE rb_voti.materia = id_materia AND anno = $year AND alunno = $student AND docente = $teacher $int_time ORDER BY $field_order $order";
$res_voti = $db->executeQuery($sel_voti);

$navigation_label = "Registro personale del docente - Classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "grades_list.html.php";