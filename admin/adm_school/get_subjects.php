<?php

require_once "../../lib/start.php";

check_session(POPUP_WINDOW);
check_permission(ADM_PERM);

if(!isset($_REQUEST['sc']) || !is_numeric($_REQUEST['sc'])){
	echo "ko;Valore non valido";
	exit;
}



header("Content-type: text/plain");
try{
	$recordset = $db->executeUpdate($statement);
} catch (MySQLException $ex){
	print "ko|".$ex->getMessage()."|".$ex->getQuery();
	exit;
}

print "ok|".$msg;
exit;