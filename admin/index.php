<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM);

/**
 * selected tab?
 */
$tab = 1;
if (isset($_REQUEST['tab'])) {
	$tab = $_REQUEST['tab'];
}

$_SESSION['__school_order__'] = 0;
$title_label = "";
$admin_level = 0;
if(!$_SESSION['__user__']->isAdministrator()){
	if($_SESSION['__user__']->isPrimarySchoolAdministrator()){
		//header("Location: index_ps.php");
		$title_label = "scuola primaria";
		$_SESSION['__school_order__'] = 2;
		$admin_level = 2;
	}
	else if($_SESSION['__user__']->isMiddleSchoolAdministrator()){
		//header("Location: index_ms.php");
		$title_label = "scuola media";
		$_SESSION['__school_order__'] = 1;
		$admin_level = 1;
	}
	else if($_SESSION['__user__']->isFirstSchoolAdministrator()){
		//header("Location: index_ms.php");
		$title_label = "scuola dell'infanzia";
		$_SESSION['__school_order__'] = 3;
		$admin_level = 3;
	}
}
if (isset($_SESSION['__only_school_level__'])){
	$_SESSION['__school_order__'] = $_SESSION['__only_school_level__'];
}
unset($_SESSION['school_order']);

ini_set("display_errors", "1");

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

/*
 * controllo per la presenza del nuovo anno
 */
$count = 0;
$sel_year = "SELECT COUNT(*) FROM rb_anni WHERE data_inizio > NOW()";
try{
	$count = $db->executeCount($sel_year);
} catch (MySQLException $ex){
	$ex->redirect();
}

$exist_cdc = $exist_reg = $exist_sch = $count_data = 0;

$year = $_SESSION['__current_year__'];
$anno = $year->get_ID();
$quadrimestre = 1;
//if((date("Ymd")) > format_date($year->get_fine_quadrimestre(), IT_DATE_STYLE, SQL_DATE_STYLE, "")){
	//$quadrimestre = 2;
//}
	
/*
 * first install wizard
 */
$step = isset($_SESSION['wiz_step']) ? $_SESSION['wiz_step'] : 1;
/*
 * procedura guidata prima installazione
* first install wizard
*/
$wizard = 0;
if(isset($_SERVER['HTTP_REFERER']) && basename($_SERVER['HTTP_REFERER']) == "wiz_first_install.php?step=3"){
	$wizard = 3;
}

$navigation_label = "gestione scuola";
$drawer_label = "Amministrazione software";

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_reg_home__'] = "./";
$_SESSION['__area__'] = "admin";

include "index.html.php";
