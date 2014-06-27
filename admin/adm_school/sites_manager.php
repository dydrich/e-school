<?php

require_once "../../lib/start.php";

check_session(POPUP_WINDOW);
check_permission(ADM_PERM);

if($_POST['action'] != 2){
	$name = $db->real_escape_string(utf8_encode($_POST['titolo']));
	$address = $db->real_escape_string(utf8_encode(nl2br($_POST['testo'])));
}

switch($_POST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_sedi (nome, indirizzo) VALUES ('{$name}', '{$address}')";
		$msg = "Sede inserita correttamente";
		break;
	case 2:     // cancellazione
		$statement = "DELETE FROM rb_sedi WHERE id_sede = ".$_REQUEST['_i'];
		$msg = "Cancellazione eseguita correttamente";
		break;
	case 3:     // modifica
		$statement = "UPDATE rb_sedi set nome = '{$name}', indirizzo = '{$address}' WHERE id_sede = ".$_REQUEST['_i'];
		$msg = "Sede aggiornata correttamente";
		break;
}
header("Content-type: text/plain");
try{
	$recordset = $db->executeUpdate($statement);
} catch (MySQLException $ex){
	print "ko|".$ex->getMessage()."|".$ex->getQuery();
	exit;
}

print "ok|".$msg;
exit;