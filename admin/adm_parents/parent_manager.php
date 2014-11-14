<?php

require_once "../../lib/start.php";
require_once '../../lib/AccountManager.php';

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "");

/*
 * 1: insert
 * 2: delete
 * 3: update
 * 5: bulk delete
 * 6: resend email
 */

if ($_REQUEST['action'] != 2 && $_REQUEST['action'] != 5){
	$nome = $db->real_escape_string($_POST['nome']);
	$cognome = $db->real_escape_string($_POST['cognome']);
	$id_figli = $_POST['id_figli'];
	$uname = $_REQUEST['uname'];
	$pwd = $_REQUEST['pwd'];
	$pclear = $_REQUEST['pclear'];
	if (!isset($_REQUEST['email'])){
		$response['status'] = "ko";
		$response['message'] = "Tutti i campi del modulo sono obbligatori";
		$res = json_encode($response);
		echo $res;
		exit;
	}
	$to = $_REQUEST['email'];
	$from = "registro@istitutoiglesiasserraperdosa.it";
	$subject = "Registro elettronico {$_SESSION['__config__']['intestazione_scuola']}";
	$headers = "From: {$from}\r\n"."Reply-To: {$from}\r\n" .'X-Mailer: PHP/' . phpversion();
	$gid = 4;
	$perms = 8;
}

$db->executeUpdate("BEGIN");
switch($_REQUEST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_utenti (username, password, nome, cognome, accessi, permessi) VALUES ('$uname', '$pwd', '$nome', '$cognome', 0, 8)";
		try{
			$uid = $db->executeUpdate($statement);
			$recordset = $uid;
			$db->executeUpdate("INSERT INTO rb_gruppi_utente (gid, uid) VALUES (4, {$recordset})");
			/* insert profile for email */
			$db->executeUpdate("INSERT INTO rb_profili (id, email) VALUES ({$recordset}, '{$to}')");
			/*
			 * rb_com_users
			 */
			if (is_installed("com")) {
				$db->executeUpdate("INSERT INTO rb_com_users (uid, table_name, type) VALUES ({$uid}, 'rb_utenti', 'parent')");
			}
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case 2:     // cancellazione
		$statement = "DELETE FROM rb_utenti WHERE uid = ".$_REQUEST['_i'];
		try{
			$db->executeUpdate($statement);
			$db->executeUpdate("DELETE FROM rb_gruppi_utente WHERE uid = {$_REQUEST['_i']}");
			$db->executeUpdate("DELETE FROM rb_genitori_figli WHERE id_genitore = {$_REQUEST['_i']}");
			$db->executeUpdate("DELETE FROM rb_profili WHERE id = {$_REQUEST['_i']}");
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case 3:     // modifica
		$statement = "UPDATE rb_utenti SET nome = '$nome', cognome = '$cognome' WHERE uid = ".$_POST['_i'];
		try{
			$db->executeUpdate($statement);
			$db->executeUpdate("UPDATE rb_profili SET email = '{$to}' WHERE id = ".$_POST['_i']);
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case 5:     // bulk delete
		//echo "bulk delete";
		$ids = $_POST['ids'];
		$str_ids = implode(",", $ids);
		$statement = "DELETE FROM rb_utenti WHERE uid IN ({$str_ids})";
		try{
			$db->executeUpdate($statement);
			$db->executeUpdate("DELETE FROM rb_gruppi_utente WHERE uid IN ({$str_ids})");
			$db->executeUpdate("DELETE FROM rb_genitori_figli WHERE id_genitore IN ({$str_ids})");
			$db->executeUpdate("DELETE FROM rb_profili WHERE id IN ({$str_ids})");
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case 6:     // resend email
		$uid = $_REQUEST['_i'];
		$uname = $_REQUEST['email'];
		$to = $_REQUEST['email'];
		$from = "registro@istitutoiglesiasserraperdosa.it";
		$subject = "Registro elettronico {$_SESSION['__config__']['intestazione_scuola']}";
		$headers = "From: {$from}\r\n"."Reply-To: {$from}\r\n" .'X-Mailer: PHP/' . phpversion();
		$new_clear_passwd = AccountManager::generatePassword(8, 4);
		$new_cript_passwd = $new_clear_passwd['e'];
		$statement = "UPDATE rb_utenti SET password = '{$new_cript_passwd}' WHERE uid = {$uid}";
		try{
			$db->executeUpdate($statement);
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
}


if($_REQUEST['action'] == 1) {
	$max = $recordset;
	// send confirmation email to user
	$message = "Gentile genitore,\ncome da lei richiesto, il suo account per l'utilizzo del Registro Elettronico è stato attivato.\n ";
	$message .= "Di seguito troverà i dati e le istruzioni per accedere:\n\n";
	$message .= "username: {$uname}\npassword: ".$pclear."\n";
	$message .= "Procedura di accesso:\nvada su http://www.istitutoiglesiasserraperdosa.it e clicchi sul link 'Registro elettronico'. \nNella finestra seguente selezioni 'Area genitori', inserisca i dati di accesso e clicchi sul pulsante Login. \nInfine clicchi sul link che comparirà, per entrare nell'area riservata.\n\n";
	$message .= "Per un corretto funzionamento del software, si raccomanda di NON utilizzare il browser Internet Explorer, ma una versione aggiornata di Firefox, Google Chrome, Opera o Safari.\n";
	$message .= "Le ricordiamo che, in caso di smarrimento della password, pu&ograve; richiederne una nuova usando il link 'Password dimenticata?' presente nella pagine iniziale del Registro.\n";
	$message .= "Per qualunque problema, non esiti a contattarci.";
	mail($to, $subject, $message, $headers);
	$msg = date("d/m/Y H:i:s\n");
	$msg .= "Account genitore creato da ".$_SESSION['__user__']->getUsername()."\n";
	$msg .= "username: {$uname}\npassword: {$pclear}\nID: {$max}\n\n";
	mail("admin@istitutoiglesiasserraperdosa.it", "e-School+ log", $msg, $headers);
}
else if ($_REQUEST['action'] == 6){
	$max = $_POST['_i'];
	$message = "Gentile genitore,\ncome da lei richiesto, il suo account per l'utilizzo del Registro Elettronico è stato attivato.\n ";
	$message .= "Di seguito troverà i dati e le istruzioni per accedere:\n\n";
	$message .= "username: {$uname}\npassword: {$new_clear_passwd['c']}\n";
	$message .= "Procedura di accesso:\nvada su http://www.istitutoiglesiasserraperdosa.it e clicchi sul link 'Registro elettronico'. \nNella finestra seguente selezioni 'Area genitori', inserisca i dati di accesso e clicchi sul pulsante Login. \nInfine clicchi sul link che comparirà, per entrare nell'area riservata.\n\n";
	$message .= "Per un corretto funzionamento del software, si raccomanda di NON utilizzare il browser Internet Explorer, ma una versione aggiornata di Firefox, Google Chrome, Opera o Safari.\n";
	$message .= "Le ricordiamo che, in caso di smarrimento della password, pu&ograve; richiederne una nuova usando il link 'Password dimenticata?' presente nella pagine iniziale del Registro.\n";
	$message .= "Per qualunque problema, non esiti a contattarci.";
	mail($to, $subject, $message, $headers);
}
else if ($_REQUEST['action'] != 5) {
	$max = $_POST['_i'];
}
/*
 * delete sons in order to reinsert them
*/
if($_REQUEST['action'] != 1 && $_REQUEST['action'] != 6 && $_REQUEST['action'] != 5){
	$del = "DELETE FROM rb_genitori_figli WHERE id_genitore = ".$_REQUEST['_i'];
	try{
		$rdel = $db->executeUpdate($del);
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
		$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
		$res = json_encode($response);
		echo $res;
		exit;
	}
}

if($_REQUEST['action'] != 2 && $_REQUEST['action'] != 6 && $_REQUEST['action'] != 5){
	$figli = explode(",", $id_figli);
	foreach($figli as $figlio){
		$ins = "INSERT INTO rb_genitori_figli VALUES ($max, $figlio)";
		try{
			$rins = $db->executeUpdate($ins);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
	}
}

$response['message'] = "Operazione completata con successo";
$res = json_encode($response);
echo $res;
exit;
