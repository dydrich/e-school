<?php

include "../lib/start.php";

header("Content-type: text/plain");

$uname = $db->real_escape_string($_POST['uname']);
$pass = $db->real_escape_string($_POST['pass']);

$sel_user = "SELECT gruppi FROM rb_utenti WHERE username = '{$uname}' AND password = '".trim($pass)."'";
$_SESSION['sel'] = $sel_user;
try{
	$res_user = $db->executeCount($sel_user);
} catch (MySQLException $ex){
	print("ko;".$ex->getMessage());
	exit;
}
if($res_user == ""){
	print("ko;Nessun utente presente: $sel_user");
	exit;
}
$gid = explode(",", $res_user);
if(in_array("1", $gid)){
	$now = time();
	$_SESSION['__admin_authentication_timeout__'] = $now + ACTIVE_ADMIN_SECONDS;
	print "ok";
}
else
	print "ko";
exit;