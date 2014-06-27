<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: application/json");

$student = $_POST['alunno'];
$param = $_POST['param'];
$value = $_POST['val'];
$anno = $_SESSION['__current_year__']->get_ID();
$q = $_POST['q'];
$response = array("status" => ok);

$exists = $db->executeCount("SELECT id FROM rb_valutazione_parametri_pagella WHERE studente = {$student} AND anno = {$anno} AND quadrimestre = {$q} AND parametro = {$param}");
if ($exists){
	$statement = "UPDATE rb_valutazione_parametri_pagella SET giudizio = {$value} WHERE id = {$exists}";
}
else {
	$statement = "INSERT INTO rb_valutazione_parametri_pagella (studente, anno, quadrimestre, parametro, giudizio) VALUES ({$student}, {$anno}, {$q}, {$param}, {$value})";
}

try{
	$db->executeUpdate($statement);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;