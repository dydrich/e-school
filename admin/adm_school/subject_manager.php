<?php

require_once "../../lib/start.php";

check_session(POPUP_WINDOW);
check_permission(ADM_PERM|AIS_PERM|AMS_PERM|APS_PERM);

if($_POST['action'] != 2){
	$materia = $db->real_escape_string(utf8_encode($_POST['materia']));
	$parent = $_POST['parent'];
	if($parent == 0) {
		$parent = null;
	}
	$report = $_POST['report'];
	$type = $_POST['tipo'];
}
$id = $_REQUEST['_i'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch($_POST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_materie (materia, has_sons, pagella, idpadre, tipologia_scuola) VALUES ('{$materia}', 0, {$report}, ".field_null($parent, false).", {$type})";
		try{
			$db->executeUpdate("BEGIN");
			$recordset = $db->executeUpdate($statement);
			if($parent != null) {
				$db->executeUpdate("UPDATE rb_materie SET has_sons = (has_sons + 1) WHERE id_materia = $parent");
			}
			$db->executeQuery("COMMIT");
		} catch (MySQLException $ex){
			$db->executeQuery("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case 2:     // cancellazione
		$sel_parent = "SELECT idpadre FROM rb_materie WHERE id_materia = $id";
		$statement = "DELETE FROM rb_materie WHERE id_materia = $id";
		try{
			$db->executeUpdate("BEGIN");
			$idpadre = $db->executeCount($sel_parent);
			$recordset = $db->executeUpdate($statement);
			if ($idpadre != "") {
				$db->executeUpdate("UPDATE rb_materie SET has_sons = (has_sons - 1) WHERE id_materia = {$idpadre}");
			}
			$db->executeQuery("COMMIT");
		} catch (MySQLException $ex){
			$db->executeQuery("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case 3:     // modifica
		$sel_parent = "SELECT idpadre FROM rb_materie WHERE id_materia = $id";
		$statement = "UPDATE rb_materie SET materia = '$materia', pagella = $report, idpadre = ".field_null($parent, false).", tipologia_scuola = {$type} WHERE id_materia = $id";
		try{
			$db->executeUpdate("BEGIN");
			$idpadre = $db->executeCount($sel_parent);
			$recordset = $db->executeUpdate($statement);
			if($parent != null) {
				$db->executeUpdate("UPDATE rb_materie SET has_sons = (has_sons + 1) WHERE id_materia = $parent");
			}
			else {
				if ($idpadre != "") {
					$db->executeUpdate("UPDATE rb_materie SET has_sons = (has_sons - 1) WHERE id_materia = $idpadre");
				}
			}
			$db->executeQuery("COMMIT");
		} catch (MySQLException $ex){
			$db->executeQuery("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
}

echo json_encode($response);
exit;
