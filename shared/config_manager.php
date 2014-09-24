<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/09/14
 * Time: 22.17
 */
require_once "../lib/start.php";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Configurazione salvata");

$conf = $_POST['conf'];
$value = $_POST['value'];

if ($_REQUEST['action'] == "select_theme") {
	try {
		$hasConf = $db->executeCount("SELECT COUNT(*) FROM rb_configurazioni_utente WHERE configurazione = $conf AND utente = " . $_SESSION['__user__']->getUid());
		if ($hasConf > 0) {
			/*
			 * update
			 */
			$db->executeUpdate("UPDATE rb_configurazioni_utente SET valore = '$value' WHERE configurazione = $conf AND utente = " . $_SESSION['__user__']->getUid());
		}
		else {
			/*
			 * insert
			 */
			$db->executeUpdate("INSERT INTO rb_configurazioni_utente (configurazione, utente, valore) VALUES ($conf, " . $_SESSION['__user__']->getUid() . ", '$value')");
		}
	} catch (MySQLException $ex) {
		$response['status'] = "kosql";
		$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
		$response['dbg_message'] = $ex->getMessage();
		$response['query'] = $ex->getQuery();
		$res = json_encode($response);
		echo $res;
		exit;
	}

	if ($conf == 1) {
		$_SESSION['__user_theme__'] = $db->executeCount("SELECT directory FROM rb_themes WHERE id_tema = $value");
	}
}

$res = json_encode($response);
echo $res;
