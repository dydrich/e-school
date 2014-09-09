<?php

require_once "../../lib/start.php";
require_once "../../lib/Goal.php";

check_session();
check_permission(DOC_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Configurazione salvata");

$goal_manager = new Goal($_POST, new MySQLDataLoader($db));
if ($_POST['action'] == 1){
	try {
		$goal_manager->insert();
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
		$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
		$res = json_encode($response);
		echo $res;
		exit;
	}
}
else if ($_POST['action'] == 2){
	try {
		$goal_manager->delete();
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
		$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
		$res = json_encode($response);
		echo $res;
		exit;
	}
}
else if ($_POST['action'] == 3){
	try {
		$goal_manager->update();
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
		$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
		$res = json_encode($response);
		echo $res;
		exit;
	}
}

$res = json_encode($response);
echo $res;
exit;
