<?php

require_once "../../../lib/start.php";

//ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

switch ($_REQUEST['action']){
	case "change_time":
		$campo = $_REQUEST['field'];
		$value = $_REQUEST['value'];
		$value .= ":00";
		$stid = $_REQUEST['stid'];
		try{
			$begin = $db->execute("BEGIN");
			/* verifico se l'alunno e' segnato come assente */
			$is_absent = $db->executeCount("SELECT ingresso FROM rb_reg_alunni WHERE id_registro = {$_SESSION['registro']['id_reg']} AND id_alunno = {$stid}");
			if ($is_absent == "") {
				$rollback = $db->execute("ROLLBACK");
				$response['status'] = "ko_absent";
				$response['message'] = "Segnalare l'alunno come presente prima di modificare l'orario di ingresso o di uscita";
				$res = json_encode($response);
				echo $res;
				exit;
			}

			$update = "UPDATE rb_reg_alunni SET {$campo} = '{$value}', giustificata = NULL WHERE id_registro = {$_SESSION['registro']['id_reg']} AND id_alunno = {$stid}";
			$db->executeUpdate($update);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "absent":
		$stid = $_REQUEST['stid'];
		try{
			$begin = $db->execute("BEGIN");
			$update = "UPDATE rb_reg_alunni SET ingresso = NULL, giustificata = NULL, uscita = NULL WHERE id_registro = {$_SESSION['registro']['id_reg']} AND id_alunno = {$stid}";
			$db->executeUpdate($update);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "present":
		$stid = $_REQUEST['stid'];
		$sel_orari_classe = "SELECT ingresso, uscita FROM rb_reg_classi WHERE id_reg = ".$_SESSION['registro']['id_reg'];
		try{
			$res_orari_classe = $db->executeQuery($sel_orari_classe);
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$orari = $res_orari_classe->fetch_assoc();
		$update = "UPDATE rb_reg_alunni SET ingresso = '{$orari['ingresso']}', uscita = '{$orari['uscita']}', giustificata = NULL WHERE id_registro = {$_SESSION['registro']['id_reg']} AND id_alunno = {$stid}";
		try{
			$begin = $db->execute("BEGIN");
			$db->executeUpdate($update);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$response['ingresso'] = substr($orari['ingresso'], 0, 5);
		$response['uscita'] = substr($orari['uscita'], 0, 5);
		break;
}

$res = json_encode($response);
echo $res;
exit;
