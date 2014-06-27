<?php

/*
 * FILE PROVVISORIO
 * da sostituire con una gestione centralizzata di student_manager
 */

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$response = array("status" => "ok", "message" => "I dati sono stati aggiornati");
header("Content-type: application/json");

switch ($_REQUEST['area']){
	case "pers":
		$lname = $db->real_escape_string($_REQUEST['lname']);
		$fname = $db->real_escape_string($_REQUEST['fname']);
		$birth = $_REQUEST['birth'];
		if ($birth && strlen($birth) > 0){
			$birth = format_date($birth, IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		}
		$city = $db->real_escape_string($_REQUEST['city']);
		$statement = "UPDATE rb_alunni SET cognome = '{$lname}', nome = '{$fname}', data_nascita = ".field_null($birth, true).", luogo_nascita = ".field_null($city, true)." WHERE id_alunno = {$_REQUEST['stid']}";
		break;
	case "addr":
		$address = $db->real_escape_string($_REQUEST['address']);
		$city = $db->real_escape_string($_REQUEST['residence']);
		$id = $db->executeCount("SELECT id_indirizzo FROM rb_indirizzi_alunni WHERE id_alunno = {$_REQUEST['stid']}");
		if ($id){
			$statement = "UPDATE rb_indirizzi_alunni SET indirizzo = '{$address}', citta = ".field_null($city, true)." WHERE id_alunno = {$_REQUEST['stid']}";
		}
		else {
			$statement = "INSERT INTO rb_indirizzi_alunni (indirizzo, citta, id_alunno) VALUES ('{$address}', ".field_null($city, true).", {$_REQUEST['stid']})";
		}
		break;
}

try {
	$db->executeUpdate($statement);
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