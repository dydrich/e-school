<?php

require_once "../../lib/start.php";
require_once "../../lib/ScheduleModule.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

echo $_POST['action'];

switch ($_POST['action']){
	case "new_module":
		$giorni = $_POST['giorni'];
		$ore = $_POST['ore'];
		try {
			$upd = "INSERT INTO rb_moduli_orario (giorni, ore_settimanali) VALUES ({$giorni}, {$ore})";
			$idm = $db->executeUpdate($upd);
		} catch (MySQLException $ex) {
			echo "kosql|".$ex->getMessage()."|".$ex->getQuery();
			exit;
		}
		echo "ok|".$idm;
		exit;
	case "delete_day":
		$idm = $_POST['idm'];
		$cday = $_POST['cday'];
		try {
			$db->executeUpdate("DELETE FROM rb_giorni_modulo WHERE id_modulo = {$idm} AND giorno = {$cday}");
		} catch (MySQLException $ex) {
			echo "kosql|".$ex->getMessage()."|".$ex->getQuery();
			exit;
		}
		break;
	case "insert_day":
	case "update":
		$idm = $_POST['_i'];
		$cday = $_POST['cday'];
		$durata = $_POST['durata'];
		$ore = $_POST['ore'];
		$start = $_POST['start'].":00";
		$end = $_POST['end'].":00";
		$mensa = 0;
		$start_m = $end_m = "";
		if ($_POST['mensa'] == 1){
			$mensa = 1;
			$start_m = $_POST['start_m'].":00";
			$end_m = $_POST['end_m'];
		}
		$sel_day = "SELECT COUNT(*) FROM rb_giorni_modulo WHERE id_modulo = {$idm} AND giorno = {$cday}";
		$exists_day = $db->executeCount($sel_day);
		if (!$exists_day) {
			try {
				$db->executeUpdate("INSERT INTO rb_giorni_modulo (id_modulo, giorno, ingresso, uscita, durata_ora, inizio_pausa, durata_pausa) VALUES ({$idm}, {$cday}, '{$start}', '{$end}', {$durata}, ".field_null($start_m, true).", ".field_null($end_m, false).") ");
			} catch (MySQLException $ex) {
				echo "kosql|".$ex->getMessage()."|".$ex->getQuery();
				exit;
			}
		}
		else {
			try {
				$db->executeUpdate("UPDATE rb_giorni_modulo SET ingresso = '{$start}', uscita = '{$end}', durata_ora = {$durata}, inizio_pausa = ".field_null($start_m, true).", durata_pausa = ".field_null($end_m, false)." WHERE id_modulo = {$idm} AND giorno = {$cday} ");
			} catch (MySQLException $ex) {
				echo "kosql|".$ex->getMessage()."|".$ex->getQuery();
				exit;
			}
		}
}

$_SESSION['module'] = new ScheduleModule($db, $idm);
$h = $_SESSION['module']->getNumberOfHours();
$d = count($_SESSION['module']->getDays());

echo "ok|{$h}|{$d}";
exit;
