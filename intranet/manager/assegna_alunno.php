<?php

require_once "../../lib/start.php";

$alunno = $_REQUEST['alunno'];
$id = $_REQUEST['id'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$upd = "UPDATE rb_assegnazione_sostegno SET alunno = {$alunno} WHERE id = {$id}";
try {
	$update_var = $db->executeUpdate($upd);
} catch (MySQLException $ex) {
	$response['status'] = "ko";
	$response['message'] = "Si è verificato un errore";
	$response['dbg_message'] = $ex->getMessage()."--".$ex->getQuery();
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;
