<?php

/*
 * aggiorna il flag ripetente nell'alunno
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

$rip = ($_REQUEST['checked'] == "true") ? 1 : 0;
$alunno = $_REQUEST['alunno'];

$upd = "UPDATE rb_alunni SET ripetente = $rip WHERE id_alunno = $alunno";
try{
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	print "ko;".$ex->getMessage();
	exit;
}

print "ok";
exit;