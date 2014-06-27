<?php

include "../lib/start.php";

header("Content-type: application/json");
$response = array("status" => "ok");

$field = $_REQUEST['field'];
$value = $_REQUEST['value'];
$upd = "UPDATE rb_config SET valore = '$value' WHERE variabile = '$field'";
try{
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
$_SESSION['__config__'][$field] = $value;

$response['status'] = "ok";
$response['message'] = "Operazione conclusa";
$res = json_encode($response);
echo $res;
exit;