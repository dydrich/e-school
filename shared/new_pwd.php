<?php

require_once "../lib/start.php";

$new_password = $_POST['new_pwd'];
$id = $_SESSION['__user__']->getUid();
$campo = $_REQUEST['campo'];
$table = $_POST['table'];
$pwd_field = "password";

header("Content-type: text/plain");
	
$update_pwd = "UPDATE $table SET $pwd_field = '$new_password' WHERE $campo = $id";
try{
	$rs_upd_pwd = $db->executeUpdate($update_pwd);
} catch (MySQLException $ex){
	print ("ko;".$ex->getMessage());
	exit;
}

$out = "ok";
	
if($_REQUEST['from'] == "first_access")
	$out .= ";redirect";

print $out;
exit;
