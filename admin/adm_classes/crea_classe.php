<?php

/*
* crea una nuova classe
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: text/plain");

$classe = $_REQUEST['cls'];
if(!preg_match("/[1-9][a-zA-Z]/", $classe)){
	echo "ko#Formato classe non valido";
	exit;
}

$anno_corso = substr($classe, 0, 1);
$sezione 	= substr($classe, 1, 1);
$year 		= $_SESSION['__current_year__']->get_ID();

try{
	$db->executeUpdate("BEGIN");
	$insert_cls = "INSERT INTO rb_classi (anno_corso, sezione, anno_scolastico) VALUES ($anno_corso, '$sezione', $year)";
	$max = $db->executeUpdate($insert_cls);
	$db->executeUpdate("INSERT INTO rb__classi (id_classe, anno_creazione) VALUES ($max, $year)");
	$db->executeUpdate("COMMIT");
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	print "ko#".$ex->getMessage()."#".$ex->getQuery();
	exit;
}

print "ok";
exit;