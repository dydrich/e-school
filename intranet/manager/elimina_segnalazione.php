<?php

require_once "../../lib/start.php";

$alunno = $_REQUEST['id'];
$anno = $_SESSION['__current_year__']->get_ID();

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

try {
	$db->executeUpdate("DELETE FROM rb_assegnazione_sostegno WHERE alunno = {$alunno} AND anno = {$anno}");
	$db->executeUpdate("UPDATE rb_alunni SET legge104 = NULL WHERE id_alunno = {$alunno}");
} catch (MySQLException $ex) {
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
