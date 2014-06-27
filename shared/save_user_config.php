<?php

require_once "../lib/start.php";
require_once "../lib/SessionUtils.php";

header("Content-type: text/plain");

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
			echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
			exit;
		}
		break;
	case "registro_obiettivi":
		$string = $_POST['active'];
		$check_exist = $db->executeCount("SELECT COUNT(*) FROM rb_parametri_utente WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 2");
		echo $check_exist;
		try {
			if($check_exist){
				// update
				$db->executeUpdate("UPDATE rb_parametri_utente SET valore = '{$string}' WHERE id_utente = {$_SESSION['__user__']->getUid()} AND id_parametro = 2");
			}
			else {
				$db->executeUpdate("INSERT INTO rb_parametri_utente (id_utente, id_parametro, valore) VALUES ({$_SESSION['__user__']->getUid()}, 2, '{$string}')");
			}
		} catch (MySQLException $ex){
			echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
			exit;
		}
		break;
}
$ses_ut = SessionUtils::getInstance($db);
$ses_ut->registerUserConfig($_SESSION['__user__']->getUID(), "__user_config__");

echo "ok";
exit;