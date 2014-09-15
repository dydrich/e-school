<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$year = $_SESSION['__current_year__']->get_ID();
$q = $_REQUEST['q'];
$stato = $_REQUEST['stato'];

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];
$field = "stato_scrutinio";
if ($school == 'Scuola primaria'){
	$field = "stato_scrutinio_sp";
}

$upd = "UPDATE rb_pubblicazione_pagelle SET {$field} = {$stato} WHERE quadrimestre = {$q} AND anno = {$year}";
try{
	$db->executeQuery($upd);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = "Si è verificato un errore";
	$response['dbg_message'] = $ex->getMessage()."--".$ex->getQuery();
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;
