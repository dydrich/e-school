<?php

/*
 * aggiorna il cdc, inserendo docente o coordinatore
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$anno = $_SESSION['__current_year__']->get_ID();

$classe = $_POST['cls'];
$materia = $_POST['mat'];
$docente = $_POST['doc'];
$action = "";
if ($_POST['action']) {
	$action = $_POST['action'];
}

header("Content-type: text/plain");

switch ($action){
	case "del":
		$upd = "DELETE FROM rb_assegnazione_sostegno WHERE anno = {$anno} AND classe = {$classe} AND docente = {$docente}";
		break;
	case "add":
		$ore = $_POST['ore'];
		$upd = "INSERT INTO rb_assegnazione_sostegno (anno, classe, docente, ore) VALUES ({$anno}, {$classe}, {$docente}, {$ore})";
		break;
	default:
		$upd = "UPDATE rb_cdc SET id_docente = $docente WHERE id_materia = $materia AND id_anno = $anno AND id_classe = $classe";
		break;
}

try{
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
	print "ko|".$ex->getQuery()."|".$ex->getMessage();
	exit;
}
print "ok|$upd";
exit();