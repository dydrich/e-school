<?php

include "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: text/plain");

$nome = trim($_POST['nome_flusso']);
$num_step = $_POST['num_step'];
$codice_step = $_POST['codice_step'];
$gruppi = 0;
foreach($_POST['gruppi'] as $a)
$gruppi += $a;
switch($_POST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_w_workflow (richiesta, num_step, codice_step, gruppi) VALUES ('".$nome."', $num_step, '$codice_step', $gruppi)";
		$msg = "La richiesta &egrave; stata registrata correttamente";
		break;
	case 2:     // cancellazione
		$statement = "DELETE FROM rb_w_workflow WHERE id_workflow = ".$_POST['_i'];
		//print $statement;
		$msg = "La richiesta &egrave; stata cancellata correttamente";
		break;
	case 3:     // modifica
		$statement = "UPDATE rb_w_workflow SET richiesta = '$nome', num_step = $num_step, codice_step = '$codice_step', gruppi = $gruppi WHERE id_workflow = ".$_POST['_i'];
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