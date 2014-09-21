<?php

require_once "../../lib/start.php";
require_once "../../lib/ScheduleModule.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$module = new ScheduleModule($db, $_POST['module']);
$day = $module->getDay($_POST['day']);
$giorni = array(1 => "Luned&igrave;", "Marted&igrave;", "Mercoled&igrave;", "Gioved&igrave;", "Venerd&igrave;", "Sabato");
$next = $_POST['day'] + 1;
$previuos = $_POST['day'] - 1;
if ($next > 6){
	$next = 1;
}
if ($previuos < 1){
	$previuos = 6;
}
if(!$day){
	$response['status'] = "noday";
	$response['day_legend'] = $giorni[$_POST['day']];
	$response['cday'] = $_POST['day'];
	$response['next'] = $next;
	$response['previuos'] = $previuos;
	$response['next_button'] = $giorni[$next];
	$response['previous_button'] = $giorni[$previuos];
	$response['has_canteen'] = 0;
	echo json_encode($response);
	exit;
}
$hc = ($day->hasCanteen()) ? 1 : 0;

$response['durata'] = $day->getHourDuration()->getTime() / 60;
$response['ore'] = $day->getNumberOfHours();
$response['start'] = $day->getEnterTime()->toString(RBTime::$RBTIME_SHORT);
$response['end'] = $day->getExitTime()->toString(RBTime::$RBTIME_SHORT);
$response['day_legend'] = $giorni[$_POST['day']];
$response['cday'] = $_POST['day'];
$response['next'] = $next;
$response['previous'] = $previuos;
$response['next_button'] = $giorni[$next];
$response['previous_button'] = $giorni[$previuos];
$response['has_canteen'] = $hc;
if($day->hasCanteen()) {
	$response['canteen_start'] = $day->getCanteenStart()->toString(RBTime::$RBTIME_SHORT);
	$response['canteen_ends'] = $day->getCanteenDuration()->getTime() / 60;
}
else {
	$response['canteen_start'] = "";
	$response['canteen_ends'] = "";
}

echo json_encode($response);
exit;
