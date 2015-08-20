<?php

require_once "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

if($_REQUEST['action'] != 2){
	$uname = $db->real_escape_string(trim($_POST['uname']));
	$pwd = $db->real_escape_string(trim($_POST['pwd']));
	$nome = $db->real_escape_string(trim($_POST['nome']));
	$cognome = $db->real_escape_string(trim($_POST['cognome']));
	$gruppi = implode(",", $_POST['gruppi']);
	/*
	 * rb_com_users
	 */
	$type = "";
	foreach ($_POST['gruppi'] as $_g) {
		if ($_g == 2 || $_g == 3 || $_g == 5 || $_g == 6 || $_g == 7) {
			$type = "school";
		}
		else if ($_g == 4) {
			$type = "parent";
		}
	}
}
$uid = $_REQUEST['_i'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch($_POST['action']){
	case 1:     // inserimento
		$gruppi_utente = explode(",", $gruppi);
		$sel_sum = "SELECT SUM(permessi) AS sum FROM rb_gruppi WHERE gid = ".$gruppi_utente[0];
		if(count($gruppi_utente) > 1){
			for($i = 1; $i < count($gruppi_utente); $i++)
				$sel_sum .= " OR gid = ".$gruppi_utente[$i];
		}
		try{
			$sum = $db->executeCount($sel_sum);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}

		// need a transaction
		try{
			$begin = $db->executeUpdate("BEGIN");
			$statement = "INSERT INTO rb_utenti (username, password, nome, cognome, accessi, permessi) VALUES ('$uname', '$pwd', '$nome', '$cognome', 0, $sum)";
           	$recordset = $db->executeUpdate($statement);
           	$uid = $recordset;
			if (is_installed("com")) {
				//echo "INSERT INTO rb_com_users (uid, table_name, type) VALUES ({$uid}, 'rb_utenti', '$type')";
				$db->executeUpdate("INSERT INTO rb_com_users (uid, table_name, type) VALUES ({$uid}, 'rb_utenti', '$type')");
			}
        } catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$msg = "Utente inserito correttamente";
		break;
   case 2:     // cancellazione
		/*
		* cancello se docente
	   	 */
       	// need a transaction
		try{
			$begin = $db->executeUpdate("BEGIN");
			$del_teacher = "DELETE FROM rb_docenti WHERE id_docente = ".$_POST['_i'];
			$d1 = $db->executeUpdate($del_teacher);
			$statement = "UPDATE rb_utenti SET attivo = 0, password = 'NON ATTIVO' WHERE uid = ".$_POST['_i'];
           	$recordset = $db->execute($statement);
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
       	}
        $msg = "Utente cancellato correttamente";
		break;
	case 3:     // modifica
		$gruppi_utente = explode(",", $gruppi);
		$sel_sum = "SELECT SUM(permessi) AS sum FROM rb_gruppi WHERE gid = ".$gruppi_utente[0];
        if(count($gruppi_utente) > 1){
			for($i = 1; $i < count($gruppi_utente); $i++)
			$sel_sum .= " OR gid = ".$gruppi_utente[$i];
		}
		try{
           	$sum = $db->executeCount($sel_sum);
        } catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$statement = "UPDATE rb_utenti SET nome = '$nome', cognome = '$cognome', permessi = $sum WHERE uid = ".$_POST['_i'];
		try{
			$recordset = $db->execute($statement);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$msg = "Utente aggiornato correttamente";
		break;
}
$_SESSION['q'] = $statement;

if($_POST['action'] != 2){
	/*
	 * gestione dei gruppi: cancello e reinserisco
	 */
	try {
		$db->executeUpdate("BEGIN");
		$db->executeUpdate("DELETE FROM rb_gruppi_utente WHERE uid = {$uid}");
		$ins = "INSERT INTO rb_gruppi_utente (gid, uid) VALUES ";
		foreach ($_POST['gruppi'] as $g) {
			$ins .= "({$g}, {$uid}),";
		}
		$ins = substr($ins, 0, -1);
		
		$db->executeUpdate($ins);
		$db->executeUpdate("COMMIT");
	} catch (MySQLException $ex) {
		$db->executeUpdate("ROLLBACK");
		$response['status'] = "kosql";
		$response['message'] = "Operazione non completata a causa di un errore";
		$response['dbg_message'] = $ex->getMessage();
		$response['query'] = $ex->getQuery();
		echo json_encode($response);
		exit;
	}
	// verifico se il record esiste
	$doc = ($_POST['action'] == 1) ? $recordset : $_POST['_i'];
	$sel_teacher = "SELECT COUNT(id_docente) FROM rb_docenti WHERE id_docente = $doc";
	$exists = $db->executeCount($sel_teacher);
	
    if(in_array(2, $_POST['gruppi'])){
		/*
		 * docente: se non esiste, inserisco il record
		 */
		if(!$exists){
			try{
				$rs = $db->executeUpdate("INSERT INTO rb_docenti (id_docente, materia) VALUES ($doc, 1)");
				$db->executeUpdate("COMMIT");
	        } catch (MySQLException $ex){
	       		$db->executeUpdate("ROLLBACK");
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
	       	}
		}
	}
	else{
		/*
		 * non docente: se esiste, cancello il record
		 */
		if($exists){
			try{
				$db->executeUpdate("DELETE FROM rb_docenti WHERE id_docente = ".$_POST['_i']);
			} catch(MySQLException $ex){
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}
		}
	}
}
try {
	$db->executeUpdate("COMMIT");
} catch (MySQLException $ex) {
	$db->executeUpdate("ROLLBACK");
	$response['status'] = "kosql";
	$response['message'] = "Operazione non completata a causa di un errore";
	$response['dbg_message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$response['message'] = $msg;
echo json_encode($response);
exit;
