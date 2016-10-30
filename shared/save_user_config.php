<?php

require_once "../lib/start.php";
require_once "../lib/SessionUtils.php";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Configurazione salvata");

switch ($_POST['field']){
	case "tipologia_prove":
		$active = $_POST['tests'];
		$string = join(";", $active);
		$check_exist = $db->executeCount("SELECT COUNT(*) FROM rb_parametri_utente WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 1");
		try {
			if($check_exist){
				// update
				$db->executeUpdate("UPDATE rb_parametri_utente SET valore = '{$string}' WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 1");
			}
			else {
				$db->executeUpdate("INSERT INTO rb_parametri_utente (id_utente, id_parametro, valore) VALUES ({$_SESSION['__user__']->getUid()}, 1, '{$string}')");
			}
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "registro_obiettivi":
		$string = $_POST['active'];
		$check_exist = $db->executeCount("SELECT COUNT(*) FROM rb_parametri_utente WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 2");
		try {
			if($check_exist){
				// update
				$db->executeUpdate("UPDATE rb_parametri_utente SET valore = '{$string}' WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 2");
			}
			else {
				$db->executeUpdate("INSERT INTO rb_parametri_utente (id_utente, id_parametro, valore) VALUES ({$_SESSION['__user__']->getUid()}, 2, '{$string}')");
			}
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "riepilogo_registro":
		$string = $_POST['active'];
		$check_exist = $db->executeCount("SELECT COUNT(*) FROM rb_parametri_utente WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 3");
		try {
			if($check_exist){
				// update
				$db->executeUpdate("UPDATE rb_parametri_utente SET valore = '{$string}' WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 3");
			}
			else {
				$db->executeUpdate("INSERT INTO rb_parametri_utente (id_utente, id_parametro, valore) VALUES ({$_SESSION['__user__']->getUid()}, 3, '{$string}')");
			}
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "data_colloqui":
		$string = $_POST['day'].";".$_POST['hour'];
		$check_exist = $db->executeCount("SELECT COUNT(*) FROM rb_parametri_utente WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 4");
		try {
			if($check_exist){
				// update
				$db->executeUpdate("UPDATE rb_parametri_utente SET valore = '{$string}' WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 4");
			}
			else {
				$db->executeUpdate("INSERT INTO rb_parametri_utente (id_utente, id_parametro, valore) VALUES ({$_SESSION['__user__']->getUid()}, 4, '{$string}')");
			}
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
}
try {
	$ses_ut = SessionUtils::getInstance($db);
	$ses_ut->registerUserConfig($_SESSION['__user__']->getUID(), "__user_config__");
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
	$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;
