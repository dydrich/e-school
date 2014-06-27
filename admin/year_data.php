<?php

require_once "../lib/SchoolYear.php";
require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$label = "";
$year = null;
$admin_level = null;
if ($_SESSION['__user__']->isAdministrator()){
	$admin_level = 0;
}
else {
	$admin_level = $_SESSION['__school_order__'];
}

if($_SESSION['__school_year__']){
	if(isset($_GET['school_order'])){
		if(!$_SESSION['__user__']->isAdministrator() && !$_SESSION['__user__']->isMiddleSchoolAdministrator() && !$_SESSION['__user__']->isPrimarySchoolAdministrator() && !$_SESSION['__user__']->isFirstSchoolAdministrator()){
			header("Location: index.php");
			exit;
		}
		if($_GET['school_order'] == 1 && !$_SESSION['__user__']->isMiddleSchoolAdministrator()
		|| $_GET['school_order'] == 2 && !$_SESSION['__user__']->isPrimarySchoolAdministrator()
		|| $_GET['school_order'] == 3 && !$_SESSION['__user__']->isFirstSchoolAdministrator()){
			header("Location: index.php");
		}
		$year = $_SESSION['__school_year__'][$_GET['school_order']];
		$label = strtolower($_SESSION['__school_level__'][$_GET['school_order']]);
	}
	else{
		$year = $_SESSION['__school_year__'][$_SESSION['__school_order__']];
		//$label = strtolower($_SESSION['__school_level__'][$_SESSION['__school_order__']]);
	}
	$days = $year->getHolydays();
	
	$hol = array();
	foreach($days as $a){
		$month = substr($a, 5, 2);
		if(!isset($hol[$month]))
			$hol[$month] = array();
		array_push($hol[$month], $a);
	}
}

$navigation_label = "Area amministrazione: gestione anno scolastico";

include "year_data.html.php";