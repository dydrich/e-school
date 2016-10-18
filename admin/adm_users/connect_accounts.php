<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/15/16
 * Time: 6:00 PM
 */
require_once "../../lib/start.php";
require_once "../../lib/AccountConnector.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

if ($_REQUEST['action'] == 'connect') {
	$uid1 = $_POST['uid1'];
	$uid2 = $_POST['uid2'];
}
else {
	$id = $_POST['id'];
}

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$atc = new \eschool\AccountConnector(new MySQLDataLoader($db));
if ($_REQUEST['action'] == 'connect') {
	$accounts = $atc->connect($uid1, $uid2);
}
else {
	$atc->disconnect($id);
}


$res = json_encode($response);
echo $res;
exit;