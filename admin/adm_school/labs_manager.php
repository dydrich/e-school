<?php

require_once "../../lib/start.php";
require_once "../../lib/Classroom.php";

check_session(POPUP_WINDOW);
check_permission(ADM_PERM);

if($_POST['action'] != 2){
	$name = $db->real_escape_string($_POST['titolo']);
	$venue = $_REQUEST['sede'];
}
else {
	$name = null;
	$venue = null;
}

header("Content-type: application/json");
$response = ["status" => "ok", "message" => "Operazione completata"];

$classroom = new \eschool\Classroom($_REQUEST['_i'], new MySQLDataLoader($db), $name, $venue);

try {
	switch($_POST['action']){
		case 1:     // inserimento
			$classroom->insert();
			$msg = "Aula inserita correttamente";
			break;
		case 2:     // cancellazione
			$classroom->delete();
			$msg = "Cancellazione eseguita correttamente";
			break;
		case 3:     // modifica
			$classroom->update();
			$msg = "Aula aggiornata correttamente";
			break;
	}
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
