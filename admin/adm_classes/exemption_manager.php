<?php
/**
 * Created by PhpStorm.
 *
 * gestisce gli esoneri dalla religione
 *
 * User: riccardo
 * Date: 02/03/15
 * Time: 18.27
 */
require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$student = $_REQUEST['student'];
$cls = $_REQUEST['cls'];
$action = $_REQUEST['action'];
$year = $_SESSION['__current_year__']->get_ID();

if ($action == "check") {
	$query = "INSERT INTO rb_esoneri_religione (anno, alunno, classe) VALUES ($year, $student, $cls)";
}
else {
	$query = "DELETE FROM rb_esoneri_religione WHERE anno = $year AND alunno = $student";
}

try {
	$db->executeUpdate($query);
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

echo json_encode($response);
exit;
