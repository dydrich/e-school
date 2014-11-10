<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

if($_SESSION['__school_order__'] != 0){
	header("Location: year_data.php");
	exit;
}

$admin_level = $_SESSION['__school_order__'];

$action = "new";
if(isset($_REQUEST['do'])){
	$action = $_REQUEST['do'];
}
else{
	$action = "basic_update";
}

$year = $_SESSION['__current_year__'];
list($d, $m, $y) = explode("/", format_date($year->get_data_chiusura(), SQL_DATE_STYLE, IT_DATE_STYLE, "/"));
if ($action == "new"){
	$y++;
}

$navigation_label = "gestione scuola";
$drawer_label = "Gestione anno scolastico";

include "year.html.php";
