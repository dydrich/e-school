<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

header("Content-type: text/plain");

$student = $_REQUEST['std'];
$_class = $_REQUEST['cl'];
if($_class == "0")
	$_class = "NULL";

try{
	$upd = "UPDATE fc_alunni SET id_classe = $_class WHERE id_alunno = $student";
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	print ("ko|".$ex->getMessage()."|$upd");
	exit;
}

print "ok|$student";
exit;