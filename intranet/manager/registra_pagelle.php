<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

header("Content-type: text/plain");

list($date, $h) = explode(" ", $_REQUEST['data']);
$date = format_date($date, IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$h = $h.":00";

$year = $_SESSION['__current_year__']->get_ID();
$q = $_REQUEST['q'];

$field = array("data_pubblicazione", "ora_pubblicazione");
if ($school == 'Scuola primaria'){
	$field[0] = "data_pubblicazione_sp";
	$field[1] = "ora_pubblicazione_sp";
}
$upd = "UPDATE rb_pubblicazione_pagelle SET {$field[0]} = '{$date}', {$field[1]} = '$h' WHERE quadrimestre = {$q} AND anno = {$year}";
try{
	$db->executeQuery($upd);
} catch (MySQLException $ex){
	echo "kosql#".$ex->getMessage()."#".$ex->getQuery();
	exit;
}

echo "ok";

?>