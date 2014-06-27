<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

header("Content-type: text/plain");

$student = $_REQUEST['stid'];
$grade = $_REQUEST['grade'];

try{
	$upd = "UPDATE fc_alunni SET voto = $grade WHERE id_alunno = $student";
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	print ("ko|".$ex->getMessage()."|$upd");
	exit;
}

$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM fc_alunni WHERE classe_provenienza = ".$_REQUEST['cl'];
$avg = $db->executeCount($sel_avg);

print "ok|$avg";
exit;