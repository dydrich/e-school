<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$id_alunno = $_REQUEST['alunno'];
$id_registro = $_REQUEST['registro'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");
$upd_assenza = "UPDATE rb_reg_alunni SET giustificata = NOW() WHERE id_alunno = {$id_alunno} AND id_registro = {$id_registro}";
try{
	$db->executeUpdate($upd_assenza);
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