<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__school_order__'] = "0";
$title_label = "";
$admin_level = getAdminLevel($_SESSION['__user__']);
if(!$_SESSION['__user__']->isAdministrator()){
	if($_SESSION['__user__']->isPrimarySchoolAdministrator()){
		//header("Location: index_ps.php");
		$title_label = "scuola primaria";
		$_SESSION['__school_order__'] = 2;
	}
	else if($_SESSION['__user__']->isMiddleSchoolAdministrator()){
		//header("Location: index_ms.php");
		$title_label = "scuola media";
		$_SESSION['__school_order__'] = 1;
	}
	else if($_SESSION['__user__']->isFirstSchoolAdministrator()){
		//header("Location: index_ms.php");
		$title_label = "scuola dell'infanzia";
		$_SESSION['__school_order__'] = 3;
	}
}
if (isset($_SESSION['__only_school_level__']) && $_SESSION['__only_school_level__']){
	$_SESSION['__school_order__'] = $_SESSION['__only_school_level__'];
}
unset($_SESSION['school_order']);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$navigation_label = "statistiche registro";
$drawer_label = "Statistiche registro elettronico ". $title_label;

include_once "statistiche_registro.html.php";
