<?php

/*
* cancella le classi terze, impostando il flag attivo a 'L' per tutti gli alunni non marcati come ripetenti
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|AMS_PERM|APS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$school_order = $_REQUEST['school_order'];
if ($school_order == 1){
	$term_cls = 3;
	$attivo = 'L';
}
else {
	$term_cls = 5;
	$attivo = '0';
}

$t_step = $_REQUEST['t_step'];
if ($t_step <= $_SESSION['__new_classes_step__']){
	$response['status'] = "wrong_step";
	$response['message'] = "Funzione completata";
	echo json_encode($response);
	exit;
}

try{
	$db->execute("BEGIN");
	$idp = $db->executeCount("SELECT MAX(id_pubblicazione) FROM rb_pagelle ");
	$db->executeUpdate("UPDATE rb_alunni, rb_classi SET ripetente = 0 WHERE rb_alunni.id_classe = rb_classi.id_classe AND ordine_di_scuola = {$school_order}");
	$sel_ripet = "UPDATE rb_alunni, rb_pagelle, rb_esiti, rb_classi SET ripetente = 1, rb_alunni.id_classe = NULL WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno AND rb_pagelle.esito = id_esito AND positivo = 0 AND rb_alunni.id_classe = rb_classi.id_classe AND ordine_di_scuola = {$school_order} AND id_pubblicazione = {$idp}";
	$del_students = "UPDATE rb_alunni, rb_pagelle, rb_esiti, rb_classi SET attivo = '{$attivo}' WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno AND rb_pagelle.esito = id_esito AND positivo = 1 AND rb_alunni.id_classe = rb_classi.id_classe AND ordine_di_scuola = {$school_order} AND anno_corso = {$term_cls} AND id_pubblicazione = {$idp}";
	//echo $del_students;
	$db->executeUpdate($sel_ripet);
	$db->executeUpdate($del_students);
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

try{
	$db->executeUpdate("UPDATE rb__classi, rb_classi SET attiva = 0 WHERE rb__classi.id_classe = rb_classi.id_classe AND anno_corso = {$term_cls} AND ordine_di_scuola = {$school_order} ");
	$del_classes = "UPDATE rb_classi SET anno_corso = 0 WHERE anno_corso = {$term_cls} AND ordine_di_scuola = {$school_order}";
	$db->executeUpdate($del_classes);
	$db->executeUpdate("UPDATE rb_config SET valore = 2 WHERE variabile = 'stato_avanzamento_nuove_classi_{$school_order}'");
	$db->executeUpdate("COMMIT");
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$_SESSION['__new_classes_step__'] = 2;
echo json_encode($response);
exit;