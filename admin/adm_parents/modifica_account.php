<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

$uname = $db->real_escape_string($_POST['nick']);
$pwd = $db->real_escape_string($_POST['pwd']);

$update = "UPDATE rb_utenti SET username = '$uname', password = '$pwd' WHERE uid = ".$_REQUEST['id'];
try{
	$result = $db->executeUpdate($update);
} catch (MySQLException $ex){
	$er = "ko|".$ex->getQuery()."|".$ex->getMessage();
	print $er;
	exit;
}

print "ok";
exit;