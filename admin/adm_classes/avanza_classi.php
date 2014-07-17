<?php

/*
* avanza di un anno le classi seconde o prime
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|AMS_PERM|APS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$school_order = $_REQUEST['school_order'];
if ($school_order == 1){
	$term_cls = 2;
}
else {
	$term_cls = 4;
}
$t_step = $_REQUEST['t_step'];
if ($t_step <= $_SESSION['__new_classes_step__']){
	$response['status'] = "wrong_step";
	$response['message'] = "Funzione completata";
	echo json_encode($response);
	exit;
}

$year = $db->executeCount("SELECT MAX(id_anno) FROM rb_anni");

//echo $upd_classes;
//exit;
try{
	$db->execute("BEGIN");
	for ($i = $term_cls; $i > 0; $i--){
		$new_cls = $i + 1;
		$upd_classes = "UPDATE rb_classi SET anno_corso = {$new_cls}, anno_scolastico = {$year} WHERE anno_corso = {$i} AND ordine_di_scuola = {$school_order}";
		$db->executeUpdate($upd_classes);
		//echo $upd_classes."<br>";
	}
	$db->executeUpdate("UPDATE rb_config SET valore = 3 WHERE variabile = 'stato_avanzamento_nuove_classi_{$school_order}'");
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

echo json_encode($response);
exit;