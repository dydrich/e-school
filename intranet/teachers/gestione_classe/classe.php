<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$utils = SessionUtils::getInstance($db);
$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$holydays = $school_year->getHolydays();

$vacance = false;
if((date("m-d") > "06-30") && (date("m-d") < "09-01")){
	$vacance = true;
}
if(!$vacance){
	$day1 = date("Y-m-d");
	if (date("H:i") > "14:30"){
		$day1 = date("Y-m-d", strtotime($day1." +1 days"));
	}
	while (date("w", strtotime($day1)) == 0 || in_array($day1, $holydays)){
		$day1 = date("Y-m-d", strtotime($day1." +1 days"));
	}
	$day2 = date("Y-m-d", strtotime($day1." +1 days"));
	while (date("w", strtotime($day2)) == 0 || in_array($day2, $holydays)){
		$day2 = date("Y-m-d", strtotime($day2." +1 days"));
	}
	$day3 = date("Y-m-d", strtotime($day2." +1 days"));
	while (date("w", strtotime($day3)) == 0 || in_array($day3, $holydays)){
		$day3 = date("Y-m-d", strtotime($day3." +1 days"));
	}
	
	$sel_today_hw = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio LIKE '$day1%' AND rb_impegni.tipo = 2 ORDER BY data_inizio DESC";
	$res_today_hw = $db->execute($sel_today_hw);
	$sel_today_act = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_fine LIKE '$day1%' AND rb_impegni.tipo = 1 ORDER BY data_inizio DESC";
	$res_today_act = $db->execute($sel_today_act);
	
	$sel_tomorrow_hw = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio LIKE '$day2%' AND rb_impegni.tipo = 2 ORDER BY data_inizio DESC";
	$res_tomorrow_hw = $db->execute($sel_tomorrow_hw);
	$sel_tomorrow_act = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_fine LIKE '$day2%' AND rb_impegni.tipo = 1 ORDER BY data_inizio DESC";
	$res_tomorrow_act = $db->execute($sel_tomorrow_act);
	
	$sel_day3_hw = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio LIKE '$day3%' AND rb_impegni.tipo = 2 ORDER BY data_inizio DESC";
	$res_day3_hw = $db->execute($sel_day3_hw);
	$sel_day3_act = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_fine LIKE '$day3%' AND rb_impegni.tipo = 1 ORDER BY data_inizio DESC";
	$res_day3_act = $db->execute($sel_day3_act);
	
	setlocale(LC_TIME, "it_IT");
	$tom 	 = ucfirst(utf8_encode(strftime("%A %d %B", strtotime($day2))));
	$tod 	 = ucfirst(utf8_encode(strftime("%A %d %B", strtotime($day1))));
	$post_tm = ucfirst(utf8_encode(strftime("%A %d %B", strtotime($day3))));
}

$navigation_label = "Registro elettronico - Gestione classe";

include "classe.html.php";

?>