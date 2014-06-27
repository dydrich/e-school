<?php

/*
 * rinomina o cancella la classe
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: text/plain");

$classe = $_POST['cls'];
$year 	= $_SESSION['__current_year__']->get_ID();
$school_level = $_POST['ordine_di_scuola'];
$tempo_prolungato = $musicale = 0;
if(isset($_POST['tempo_prolungato'])){
	$tempo_prolungato = $_POST['tempo_prolungato'];
}
if(isset($_POST['musicale'])){
	$musicale = $_POST['musicale'];
}

switch($_POST['action']){
	case 'delete':
		$query = "DELETE FROM rb_classi WHERE id_classe = $classe";
		break;
	case 'update':
		$query = "UPDATE rb_classi SET anno_corso = {$_POST['anno_corso']}, sezione = '{$_POST['sezione']}', sede = {$_POST['sede']}, tempo_prolungato = {$tempo_prolungato}, musicale = {$musicale}, ordine_di_scuola = {$school_level} WHERE id_classe = $classe";
		break;
	case 'upgrade':
		$field = $_POST['field'];
		$value = $_POST['value'];
		$is_char = $_POST['is_char'];
		$query = "UPDATE rb_classi SET $field = ".field_null($value, $is_char)." WHERE id_classe = {$classe}";
		break;
	case 'insert':
		$query = "INSERT INTO rb_classi (anno_corso, sezione, sede, tempo_prolungato, musicale, anno_scolastico, ordine_di_scuola) VALUES ({$_POST['anno_corso']}, '{$_POST['sezione']}', {$_POST['sede']}, {$tempo_prolungato}, {$musicale}, {$year}, {$school_level})";
		break;
}


try{
	if($_POST['action'] == "insert"){
		$db->executeUpdate("BEGIN");
	}
	$res = $db->executeUpdate($query);
	if($_POST['action'] == "insert"){
		$db->executeUpdate("INSERT INTO rb__classi (id_classe, anno_creazione) VALUES ($res, $year)");
		$db->executeUpdate("COMMIT");
	}
} catch (MySQLException $ex){
	if($_POST['action'] == "insert"){
		$db->executeUpdate("ROLLBACK");
	}
	print "ko|".$ex->getQuery()."|".$ex->getMessage();
	exit;
}

print "ok";
exit;