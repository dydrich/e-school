<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: text/plain");

$campo = $db->real_escape_string($_REQUEST['campo']);
$table = $db->real_escape_string($_REQUEST['table']);
$id = $db->real_escape_string($_REQUEST['uid']);
$new_password = $db->real_escape_string($_REQUEST['n_p']);
$pwd_field = "password";

$update_pwd = "UPDATE $table SET $pwd_field = '$new_password' WHERE $campo = $id";
try{
	$db->executeQuery($update_pwd);
} catch (MySQLException $ex){
	$ex->fake_alert();
	exit;
}

$out = "ok";

print $out;
exit;