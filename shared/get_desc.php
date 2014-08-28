<?php

include "../lib/start.php";

check_session();

$id_impegno = $_POST['id_impegno'];
$tipo_impegno = $_POST['tipo'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "ok");

$sel_desc = "SELECT descrizione, note FROM rb_impegni WHERE id_impegno = $id_impegno";
try{
	$res_desc = $db->executeQuery($sel_desc);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
$desc = $res_desc->fetch_assoc();
$response['descrizione'] = $desc['descrizione'];
$response['note'] = $desc['note'];

$res = json_encode($response);
echo $res;
exit;
