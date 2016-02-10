<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 09/02/16
 * Time: 15.27
 */
require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$anno = $_SESSION['__current_year__']->get_ID();

$action = "";
if (isset($_POST['action'])) {
	$action = $_POST['action'];
}

header("Content-type: application/json");
$response = ["status" => "ok", "message" => "Operazione completata"];

if ($action == 'add') {
	$classe = $_POST['cls'];
	$rapp = $_POST['rapp'];
	$query = "INSERT INTO rb_rappresentanti_classe (anno, classe, genitore) VALUES ({$anno}, {$classe}, {$rapp})";
	$user = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$rapp}");
	$response['user'] = $user;
}
else {
	$id = $_REQUEST['id'];
	$response['uid'] = $db->executeCount("SELECT genitore FROM rb_rappresentanti_classe WHERE id = {$id}");
	$query = "DELETE FROM rb_rappresentanti_classe WHERE id = {$id}";
}

try{
	$id = $db->executeUpdate($query);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$response['id'] = $id;
echo json_encode($response);
exit;
