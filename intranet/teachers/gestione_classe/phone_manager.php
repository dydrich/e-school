<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 03/05/15
 * Time: 18.16
 */
require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$stid = $_REQUEST['stid'];

$main = 0;
if (isset($_REQUEST['main_p']) && $_REQUEST['main_p'] != 0) {
	$main = $_REQUEST['main_p'];
}

$response = array("status" => "ok", "message" => "Operazione completata");
header("Content-type: application/json");

switch ($_REQUEST['action']) {
	case "new":
		$number = $db->real_escape_string($_REQUEST['number']);
		$desc = $db->real_escape_string($_REQUEST['desc']);
		$sql = "INSERT INTO rb_telefoni_alunni (id_alunno, telefono, descrizione, principale) VALUES ($stid, '$number', '$desc', 0)";
		break;
	case "del":
		$idp = $_REQUEST['idp'];
		$sql = "DELETE FROM rb_telefoni_alunni WHERE id = $idp";
		break;
	case "upd":
		$idp = $_REQUEST['idp'];
		$f1 = "number_".$idp;
		$f2 = "desc_".$idp;
		$number = $db->real_escape_string($_REQUEST[$f1]);
		$desc = $db->real_escape_string($_REQUEST[$f2]);

		$sql = "UPDATE rb_telefoni_alunni SET telefono = '$number', descrizione = '$desc' WHERE id = $idp";
		break;
}

try {
	$idp = $db->executeUpdate($sql);
	if ($_REQUEST['action'] == "new" && isset($_REQUEST['main_phone']) && $_REQUEST['main_phone'] == 1) {
		$main = $idp;
	}
	if ($_REQUEST['action'] != "del" && $main != 0) {
		$db->executeUpdate("UPDATE rb_telefoni_alunni SET principale = 0 WHERE id_alunno = $stid");
		$db->executeUpdate("UPDATE rb_telefoni_alunni SET principale = 1 WHERE id_alunno = $stid AND id = $main");
	}
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

if ($_REQUEST['action'] != "del") {
	$res['phone'] = $number;
	$res['desc'] = $desc;
}
$res['main'] = $main;

$res = json_encode($response);
echo $res;
exit;
