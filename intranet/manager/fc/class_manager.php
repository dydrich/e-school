<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

header("Content-type: text/plain");

$id = $_REQUEST['id'];
$sede = $_REQUEST['sede'];
$tp = $_REQUEST['tp'];
$classe = substr($_REQUEST['name'], 0, 1);
$sezione = substr($_REQUEST['name'], 1, 1);

switch($_REQUEST['action']){
	case "1":
		$sel_max = "SELECT MAX(id_classe) FROM nuove_classi";
		$max = $db->executeCount($sel_max);
		$id = ++$max;
		$statement = "INSERT INTO nuove_classi (id_classe, classe, sezione, sede, tempo_prolungato) VALUES ($id, $classe, '".$sezione."', $sede, $tp)";
		$error = "Errore nella creazione della classe";
		break;
	case "2":
		$statement = "DELETE FROM nuove_classi WHERE id_classe = $id";
		$error = "Errore nella cancellazione della classe";
		break;
}
try{
	$res = $db->executeQuery($statement);
} catch (MySQLException $ex){
	print ("ko|$error|".$ex->getQuery()."|".$ex->getMessage());
	exit;
}

print "ok|$id";
exit;