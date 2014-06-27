<?php

require_once "../../lib/start.php";
require_once "../../lib/ScheduleModule.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

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
	echo "noday|||||".$giorni[$_POST['day']]."|".$_POST['day']."|".$next."|".$previuos."|".$giorni[$next]."|".$giorni[$previuos]."|0|";
	exit;
}
$hc = ($day->hasCanteen()) ? 1 : 0;

$string = "ok|".($day->getHourDuration()->getTime() / 60)."|";
$string .= $day->getNumberOfHours()."|".$day->getEnterTime()->toString(RBTime::$RBTIME_SHORT)."|".$day->getExitTime()->toString(RBTime::$RBTIME_SHORT)."|";
$string .= $giorni[$_POST['day']]."|".$_POST['day']."|".$next."|".$previuos."|".$giorni[$next]."|".$giorni[$previuos]."|";
$string .= $hc."|";
if($day->hasCanteen()){
	$string .= $day->getCanteenStart()->toString(RBTime::$RBTIME_SHORT)."|".($day->getCanteenDuration()->getTime() / 60)."|";
}
else {
	$string ."||";
}

echo $string;
exit;
