<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/3/16
 * Time: 5:12 PM
 * gestore tipologia di incarico
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$name = $perms = $msg = null;

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch($_POST['action']){
	case 'new':     // inserimento
		$name = $db->real_escape_string($_POST['name']);
		$max = $db->executeCount("SELECT MAX(permessi) FROM rb_access_permissions");
		$perms = $max * 2;
		$statement = "INSERT INTO rb_ruoli (permessi, nome) VALUES ({$perms}, '{$name}')";
		$msg = "Ruolo inserito correttamente";
		break;
	case 'del':     // cancellazione
		$statement = "DELETE FROM rb_ruoli WHERE rid = ".$_REQUEST['rid'];
		$msg = "Cancellazione eseguita correttamente";
		break;
	case 'upd':     // modifica
		$name = $db->real_escape_string($_POST['name']);
		$statement = "UPDATE rb_ruoli set nome = '{$name}' WHERE rid = ".$_REQUEST['rid'];
		$msg = "Ruolo aggiornato correttamente";
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
