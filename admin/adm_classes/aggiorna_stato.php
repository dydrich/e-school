<?php

/*
 * aggiorna lo stato di avanzamento dell'operazione di attivazione classi per il nuovo anno
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

$step = $_REQUEST['step'];

$upd = "UPDATE rb_config SET valore = $step WHERE variabile = 'stato_avanzamento_nuove_classi'";
try{
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	print "ko;".$ex->getMessage();
	exit;
}

$_SESSION['__new_classes_step__'] = $step;

print "ok";
exit;