<?php

require_once "../../lib/start.php";

check_session(POPUP_WINDOW);
check_permission(ADM_PERM);

if($_POST['action'] != 2){
	$name = $db->real_escape_string(utf8_encode($_POST['titolo']));
	$venue = $_REQUEST['sede'];
}

header("Content-type: application/json");
$response = ["status" => "ok", "message" => "Operazione completata"];

switch($_POST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_aule_speciali (nome, sede) VALUES ('{$name}', {$venue})";
		$msg = "Aula inserita correttamente";
		break;
	case 2:     // cancellazione
		$statement = "DELETE FROM rb_aule_speciali WHERE id_lab = ".$_REQUEST['_i'];
		$msg = "Cancellazione eseguita correttamente";
		break;
	case 3:     // modifica
		$statement = "UPDATE rb_aule_speciali set nome = '{$name}', sede = {$venue} WHERE id_lab = ".$_REQUEST['_i'];
		$msg = "Aula aggiornata correttamente";
		break;
}

try{
	$recordset = $db->executeUpdate($statement);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = "Operazione non completata a causa di un errore";
	$response['dbg_message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$response['message'] = $msg;

echo json_encode($response);
exit;
