<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: text/plain");

$names = array();
$sel_names = "SELECT username FROM rb_utenti ";
try{
	$res = $db->executeQuery($sel_names);
} catch (MySQLException $ex){
	print $ex->getMessage();
	exit;
} catch (Exception $e){
	print $e->getMessage();
	exit;
}
while($us = $res->fetch_assoc()){
	array_push($names, $us['username']);
}
$res->free();

$login = get_login($names, $_POST['nome'], $_POST['cognome']);

print $login;
exit;