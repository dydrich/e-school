<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 9/4/16
 * Time: 3:12 AM
 */
include "../lib/start.php";
include "../lib/Calendar.php";

header("Content-type: application/json");
$response = ['status' => 'ok', 'message' => 'ok'];

$cls = $_POST['cls'];
$cal = new \eschool\Calendar(null, $cls, null, null, new MySQLDataLoader($db));
$data = $cal->getActivities();
$response['data'] = $data;

echo json_encode($response);
exit;