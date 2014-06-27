<?php

include "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: text/plain");

$nome = trim($_POST['nome_status']);
$permessi = 0;
foreach($_POST['permessi'] as $a)
	$permessi += $a;
switch($_POST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_w_status (nome, permessi) VALUES ('".$nome."', $permessi)";
		$msg = "Lo status &egrave; stato inserito correttamente";
		break;
	case 2:     // cancellazione
		$statement = "DELETE FROM rb_w_status WHERE id_status = ".$_POST['_i'];
		//print $statement;
		$msg = "Lo status &egrave; stato cancellato correttamente";
		break;
	case 3:     // modifica
		$statement = "UPDATE rb_w_status SET nome = '$nome', permessi = $permessi WHERE id_status = ".$_POST['_i'];
		//print $statement;
		$msg = "Lo status &egrave; stato aggiornato correttamente";
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