<?php

include "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: text/plain");

$nome = utf8_encode(trim($_POST['nome_step']));
$ufficio = $_POST['ufficio'];
switch($_POST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_w_step (descrizione, ufficio) VALUES ('".$nome."', $ufficio)";
		$msg = "Lo step &egrave; stato inserito correttamente";
		break;
	case 2:     // cancellazione
		$statement = "DELETE FROM rb_w_step WHERE id_step = ".$_POST['_i'];
		//print $statement;
		$msg = "Lo step &egrave; stato cancellato correttamente";
		break;
	case 3:     // modifica
		$statement = "UPDATE rb_w_step SET descrizione = '$nome', ufficio = $ufficio WHERE id_step = ".$_POST['_i'];
		//print $statement;
		$msg = "Lo step &egrave; stato aggiornato correttamente";
		break;
}

try{
	$recordset = $db->executeUpdate($statement);
} catch (MySQLException $ex){
	print "ko|".$ex->getMessage()."|".$ex->getQuery();
	exit;
}

print "ok";
exit;

?>