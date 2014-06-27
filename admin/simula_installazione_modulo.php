<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: text/plain");

$mod = $_REQUEST['field'];
$val = $_REQUEST['value'];

$update = "UPDATE rb_modules SET active = $val WHERE code_name = '$mod'";
$_SESSION['update'] = $update;
try{
	$db->executeQuery($update);
} catch (MySQLException $ex){
	$ex->fake_alert();
	exit;
}

$sel_modules = "SELECT id, code_name, active FROM rb_modules";
$res_modules = $db->executeQuery($sel_modules);
$_SESSION['__modules__'] = array();
while($mod = $res_modules->fetch_assoc()){
	$_SESSION['__modules__'][$mod['code_name']]['id'] = $mod['id'];
	$_SESSION['__modules__'][$mod['code_name']]['installed'] = $mod['active'];
}

$out = "ok";
print $out;
exit;