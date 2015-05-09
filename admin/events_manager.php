<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 09/05/15
 * Time: 19.54
 */
require_once "../lib/start.php";
require_once '../lib/EventType.php';

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$action = $_POST['action'];
$id = $_REQUEST['id'];
$data = null;

if ($action != "delete") {
	$data['tipo'] = $_REQUEST['tipo'];
	$data['descrizione'] = $_REQUEST['descrizione'];
	$data['numeric1'] = $_REQUEST['numeric1'];
	$data['numeric2'] = $_REQUEST['numeric2'];
	$data['text1'] = $_REQUEST['text1'];
	$data['text2'] = $_REQUEST['text2'];
	$data['float1'] = $_REQUEST['float1'];
}

$event = new EventType($id, new MySQLDataLoader($db), $data);

try {
	switch ($action) {
		case "delete":
			$event->delete();
			break;
		case "insert":
			$event->insert();
			break;
		case "update":
			$event->update();
			break;
	}
} catch (MySQLException $ex) {
	$response['status'] = "ko";
	$response['message'] = "Errore di sistema";
	$response['dbg_message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;
