<?php

/*
* avanza di un anno le classi seconde o prime
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

$cls = $_REQUEST['cls'];
$new_cls = $cls + 1;
if($cls == 2) $step = 3;
if($cls == 1) $step = 4;

$year = $db->executeCount("SELECT MAX(id_anno) FROM rb_anni");

$db->execute("BEGIN");
$upd_classes = "UPDATE rb_classi SET anno_corso = {$new_cls}, anno_scolastico = {$year} WHERE anno_corso = {$cls} AND ordine_di_scuola = 1";
//echo $upd_classes;
//exit;
try{
	$db->executeUpdate($upd_classes);
	$db->executeUpdate("UPDATE rb_config SET valore = $step WHERE variabile = 'stato_avanzamento_nuove_classi'");
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	print "ko#".$ex->getMessage();
	exit;
}

if($cls == 2){
	$_SESSION['__new_classes_step__'] = $step;
}
else { 
	$_SESSION['__new_classes_step__'] = $step;
}
print "ok";
exit;