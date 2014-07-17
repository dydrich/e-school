<?php

/*
* crea le nuove classi prime: ultimo step della attivazione classi per nuovo anno
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|AMS_PERM|APS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$school_order = $_REQUEST['school_order'];
$classi = explode(",", $_REQUEST['cls']);
$year = $_SESSION['__current_year__']->get_ID();

foreach ($classi as $a){
	/*
	* inserisco il record in classi e uso l'id per inserire in _classi
	*/
	try{
		$sede = $db->executeCount("SELECT id_sede FROM rb_sedi WHERE (ordine_di_scuola = 0 OR ordine_di_scuola = {$school_order}) ORDER BY id_sede LIMIT 1");
		$db->executeUpdate("BEGIN");
		$insert_cls = "INSERT INTO rb_classi (anno_corso, sezione, anno_scolastico, ordine_di_scuola, sede) VALUES (1, '{$a}', {$year}, {$school_order}, {$sede})";
		$max = $db->executeUpdate($insert_cls);
		$db->executeUpdate("INSERT INTO rb__classi (id_classe, anno_creazione, annocorso_creazione, attiva) VALUES ({$max}, {$year}, 1, 1)");
		$db->executeUpdate("UPDATE rb_config SET valore = 5 WHERE variabile = 'stato_avanzamento_nuove_classi_{$school_order}'");
		$db->executeUpdate("COMMIT");
	} catch (MySQLException $ex){
		$db->executeUpdate("ROLLBACK");
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage();
		$response['query'] = $ex->getQuery();
		echo json_encode($response);
		exit;
	}
}

$_SESSION['__new_classes_step__'] = 5;
echo json_encode($response);
exit;