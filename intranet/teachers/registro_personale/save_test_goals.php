<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$docente = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$materia = $_SESSION['__materia__'];

$goals = $_REQUEST['goals'];
$test = $_REQUEST['test'];

$response = array("status" => "ok", "message" => "Operazione conclusa");
header("Content-type: application/json");

try {
	$db->executeUpdate("DELETE FROM rb_obiettivi_verifica WHERE id_verifica = {$test}");
	$db->executeUpdate("DELETE FROM rb_voti_obiettivo USING rb_voti_obiettivo JOIN rb_voti WHERE rb_voti_obiettivo.id_voto = rb_voti.id_voto AND id_verifica = {$test}");
	if (count($goals) > 0){
		foreach ($goals as $goal){
			$id = $db->executeUpdate("INSERT INTO rb_obiettivi_verifica (id_obiettivo, id_verifica) VALUES ({$goal}, {$test})");
			$db->executeUpdate("INSERT INTO rb_voti_obiettivo (id_voto, obiettivo, voto) SELECT id_voto, {$goal}, voto FROM rb_voti WHERE id_verifica = {$test}");
		}
	}
	
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;