<?php

/*
 * aggiorna lo stato di avanzamento dell'operazione di attivazione classi per il nuovo anno
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|AMS_PERM|APS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$step = $_REQUEST['step'];
$school_order = $_REQUEST['school_order'];

$upd = "UPDATE rb_config SET valore = $step WHERE variabile = 'stato_avanzamento_nuove_classi_{$school_order}'";
try{
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$_SESSION['__new_classes_step__'] = $step;

echo json_encode($response);
exit;