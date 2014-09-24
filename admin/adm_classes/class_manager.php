<?php

/*
 * rinomina o cancella la classe
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

if (isset($_POST['cls'])) {
	$classe = $_POST['cls'];
}
$year 	= $_SESSION['__current_year__']->get_ID();
if (isset($_POST['ordine_di_scuola'])) {
	$school_level = $_POST['ordine_di_scuola'];
}
$tempo_prolungato = $musicale = 0;
if(isset($_POST['tempo_prolungato'])){
	$tempo_prolungato = $_POST['tempo_prolungato'];
}
if(isset($_POST['musicale'])){
	$musicale = $_POST['musicale'];
}

switch($_POST['action']){
	case 'delete':
		$stds_count = $db->executeCount("SELECT COUNT(*) FROM rb_alunni WHERE id_classe = {$classe}");
		if ($stds_count > 0) {
			$response['message'] = "Impossibile cancellare la classe: contiene {$stds_count} alunni";
			$response['status'] = "no_del";
			echo json_encode($response);
			exit;
		}
		$query = "DELETE FROM rb_classi WHERE id_classe = $classe";
		break;
	case 'update':
		$query = "UPDATE rb_classi SET anno_corso = {$_POST['anno_corso']}, sezione = '{$_POST['sezione']}', sede = {$_POST['sede']}, tempo_prolungato = {$tempo_prolungato}, musicale = {$musicale}, ordine_di_scuola = {$school_level} WHERE id_classe = $classe";
		break;
	case 'upgrade':
		$field = $_POST['field'];
		$value = $_POST['value'];
		if ($value == 0) {
			$value = "";
		}
		$is_char = $_POST['is_char'];
		$query = "UPDATE rb_classi SET $field = ".field_null($value, $is_char)." WHERE id_classe = {$classe}";
		break;
	case 'insert':
		$query = "INSERT INTO rb_classi (anno_corso, sezione, sede, tempo_prolungato, musicale, anno_scolastico, ordine_di_scuola) VALUES ({$_POST['anno_corso']}, '{$_POST['sezione']}', {$_POST['sede']}, {$tempo_prolungato}, {$musicale}, {$year}, {$school_level})";
		break;
	/*
	 * nuove action per gestione moduli primaria
	 */
	case "insert_module":
		try {
			$id_m = $db->executeUpdate("INSERT INTO rb_moduli_primaria (anno) VALUES ({$year})");
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$response['id_modulo'] = $id_m;
		echo json_encode($response);
		exit;
	case "add_class_to_module":
		try {
			$db->executeUpdate("INSERT INTO rb_classi_modulo (id_modulo, classe) VALUES ({$_POST['idm']}, {$_POST['idc']})");
		} catch (MySQLException $ex){
			if (substr($ex->getMessage(), 0, 9) == "Duplicate") {
				$response['status'] = "ko";
				$response['dbg_message'] = $ex->getMessage();
			}
			else {
				$response['status'] = "kosql";
				$response['message'] = "Si è verificato un errore. Riprova tra qualche minuto";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
			}
			echo json_encode($response);
			exit;
		}
		echo json_encode($response);
		exit;
	case "del_class_from_module":
		try {
			$db->executeUpdate("DELETE FROM rb_classi_modulo WHERE id_modulo = {$_POST['idm']} AND classe = {$_POST['idc']}");
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		echo json_encode($response);
		exit;
}


try{
	if($_POST['action'] == "insert"){
		$db->executeUpdate("BEGIN");
	}
	$res = $db->executeUpdate($query);
	if($_POST['action'] == "insert"){
		$db->executeUpdate("INSERT INTO rb__classi (id_classe, anno_creazione, annocorso_creazione, attiva, sezione, ordine) VALUES ($res, $year, {$_POST['anno_corso']}, 1, '{$_POST['sezione']}', {$school_level})");
		$db->executeUpdate("COMMIT");
	}
	if ($_POST['action'] == "delete") {
		$db->executeUpdate("DELETE FROM rb__classi WHERE id_classe = {$classe}");
	}
} catch (MySQLException $ex){
	if($_POST['action'] == "insert"){
		$db->executeUpdate("ROLLBACK");
	}
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

echo json_encode($response);
exit;
