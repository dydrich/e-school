<?php

/**
    modifica l'argomento della lezione nella visualizzazione del docente
    e nel database: il lato client usa Ajax per l'update
*/

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$id = $_SESSION['__user__']->getUid();
$ora = $_POST['ora'];
$id_registro = $_POST['id_reg'];
$id_ora = $_POST['id_ora'];
$topic = $db->real_escape_string($_POST['topic']);

$response = array("status" => "ok", "message" => "");

header("Content-type: application/json");

$upd = "UPDATE rb_reg_firme SET argomento = '{$topic}' WHERE id_registro = {$id_registro} AND id = {$id_ora}";
try{
	$rs = $db->executeQuery($upd);
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