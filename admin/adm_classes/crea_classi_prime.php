<?php

/*
* crea le nuove classi prime: ultimo step della attivazione classi per nuovo anno
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: text/plain");

$classi = explode(",", $_REQUEST['cls']);
$year = $_SESSION['__current_year__']->get_ID();

foreach ($classi as $a){
	/*
	* inserisco il record in classi e uso l'id per inserire in _classi
	*/
	try{
		$db->executeUpdate("BEGIN");
		$insert_cls = "INSERT INTO rb_classi (anno_corso, sezione, anno_scolastico, ordine_di_scuola, sede) VALUES (1, '$a', $year, 1, 2)";
		$max = $db->executeUpdate($insert_cls);
		$db->executeUpdate("INSERT INTO rb__classi (id_classe, anno_creazione) VALUES ($max, $year)");
		$db->executeUpdate("UPDATE rb_config SET valore = 5 WHERE variabile = 'stato_avanzamento_nuove_classi'");
		$db->executeUpdate("COMMIT");
	} catch (MySQLException $ex){
		$db->executeUpdate("ROLLBACK");
		print "ko#".$ex->getMessage();
		exit;
	}
}

$_SESSION['__new_classes_step__'] = 5;
print "ok";
exit;