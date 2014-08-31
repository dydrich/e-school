<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 30/08/14
 * Time: 11.32
 */
require_once "../lib/start.php";
require_once "../lib/AccountManager.php";
require_once "../lib/RBUtilities.php";

check_session();

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "");

switch ($_REQUEST['action']) {
	case "get_pwd":
		$pwd = AccountManager::generatePassword(8, 4);
		$response['pwd'] = $pwd['c'];
		$response['epwd'] = $pwd['e'];
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case "get_user_login":
		$names = array();
		$sel_names = "SELECT username FROM rb_utenti ";
		try{
			$res_names = $db->executeQuery($sel_names);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		} catch (Exception $e){
			$response['status'] = "ko";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		while($us = $res_names->fetch_assoc()){
			array_push($names, $us['username']);
		}
		$res_names->free();
		$response['login'] = AccountManager::generateLogin($names, $_REQUEST['nome'], $_REQUEST['cognome']);
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case "get_student_login":
		$names = array();
		$sel_names = "SELECT username FROM rb_alunni ";
		try{
			$res_names = $db->executeQuery($sel_names);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		} catch (Exception $e){
			$response['status'] = "ko";
			$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		while($us = $res_names->fetch_assoc()){
			array_push($names, $us['username']);
		}
		$res_names->free();
		$response['login'] = AccountManager::generateLogin($names, $_REQUEST['nome'], $_REQUEST['cognome']);
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case "update_account":
		$uname = $db->real_escape_string($_POST['nick']);
		$pwd = $db->real_escape_string($_POST['pwd']);

		$rb = RBUtilities::getInstance($db);

		try{
			$user = $rb->loadUserFromUid($_REQUEST['id'], "parent");
			$account_manager = new AccountManager($user, new MySQLDataLoader($db));
			$account_manager->updateAccount($uname, $pwd);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = "Si è verificato un errore. Riprova tra qualche minuto";
			$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
			$res = json_encode($response);
			echo $res;
			exit;
		}
}
