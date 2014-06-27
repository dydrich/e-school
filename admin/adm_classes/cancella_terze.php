<?php

/*
* cancella le classi terze, impostando il flag attivo a 'L' per tutti gli alunni non marcati come ripetenti
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

$db->execute("BEGIN");
$idp = $db->executeCount("SELECT MAX(id_pagella) FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()}");
$sel_ripet = "UPDATE rb_alunni, rb_pagelle, rb_esiti, rb_classi SET ripetente = '1', rb_alunni.id_classe = NULL WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno AND rb_pagelle.esito = id_esito AND positivo = 0 AND rb_alunni.id_classe = rb_classi.id_classe AND id_pubblicazione = {$idp}";
$del_students = "UPDATE rb_alunni, rb_pagelle, rb_esiti, rb_classi SET attivo = 'L' WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno AND rb_pagelle.esito = id_esito AND positivo = 1 AND rb_alunni.id_classe = rb_classi.id_classe AND anno_corso = 3 AND id_pubblicazione = {$idp}";
try{
	$db->executeUpdate($sel_ripet);
	$db->executeUpdate($del_students);
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	print "ko#".$ex->getMessage();
	exit;
}

$del_classes = "DELETE FROM rb_classi WHERE anno_corso = 3";
try{
	$db->executeUpdate($del_classes);
	$db->executeUpdate("UPDATE rb_config SET valore = 2 WHERE variabile = 'stato_avanzamento_nuove_classi'");
	$db->executeUpdate("COMMIT");
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	print "ko#".$ex->getMessage();
	exit;
}

$_SESSION['__new_classes_step__'] = 2;
print "ok";
exit;