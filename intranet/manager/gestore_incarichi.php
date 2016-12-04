<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 11/30/16
 * Time: 5:36 PM
 * gestore incarichi
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch ($_POST['action']) {
	case 'new':
		$role = $_POST['role'];
		$uid = $_POST['uid'];
		try {
			$db->executeUpdate("INSERT INTO rb_ruoli_utente (rid, uid) VALUES ($role, $uid)");
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case 'del':
		$role = $_POST['role'];
		$uid = $_POST['uid'];
		try {
			$db->executeUpdate("DELETE FROM rb_ruoli_utente WHERE rid = $role AND uid = $uid");
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
}

$res = json_encode($response);
echo $res;
exit;