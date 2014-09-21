<?php

/**
    modifica la tipologia di scuola nella visualizzazione del docente
    e nel database: il lato client usa Ajax per l'update
*/

require_once "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

$id = $_POST['uid'];
$sc = $_POST['type'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

if(!is_numeric($sc)){
	$response['status'] = "ko";
	$response['message'] = "Valore inserito non valido";
	echo json_encode($response);
	exit;
}

$upd = "UPDATE rb_docenti SET tipologia_scuola = {$sc} WHERE id_docente = {$id}";
try{
	$rs = $db->executeUpdate($upd);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = "Operazione non completata a causa di un errore";
	$response['dbg_message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}
$_SESSION['q'] = $upd;

$sel_tipologie = "SELECT tipo FROM rb_tipologia_scuola WHERE id_tipo = {$sc}";
try{
	$tipo = $db->executeCount($sel_tipologie);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = "Operazione non completata a causa di un errore";
	$response['dbg_message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}
$response['tipo'] = $tipo;
echo json_encode($response);
exit;
