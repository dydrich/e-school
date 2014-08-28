<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Password modificata");

$campo = $db->real_escape_string($_REQUEST['campo']);
$table = $db->real_escape_string($_REQUEST['table']);
$id = $db->real_escape_string($_REQUEST['uid']);
$new_password = $db->real_escape_string($_REQUEST['n_p']);
$pwd_field = "password";

$update_pwd = "UPDATE $table SET $pwd_field = '$new_password' WHERE $campo = $id";
try{
	$db->executeQuery($update_pwd);
} catch (MySQLException $ex){
	$response['status'] = "ko";
	$response['message'] = "Formato orario non valido";
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;
