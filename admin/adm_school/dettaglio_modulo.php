<?php

require_once "../../lib/start.php";
require_once "../../lib/ScheduleModule.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", "0");

$admin_level = 0;
$navigation_label = "gestione scuola";
$drawer_label = "Dettaglio modulo orario";

if($_GET['idm'] != 0){
	$_SESSION['module'] = new ScheduleModule($db, $_GET['idm']);
	$module = $_SESSION['module'];
	$day = $module->getDay(1);
	$idm = $_GET['idm'];
	include "dettaglio_modulo.html.php";
}
else {
	$idm = 0;
	include "nuovo_modulo.html.php";
}
