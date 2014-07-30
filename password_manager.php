<?php

require_once './lib/start.php';
require_once './lib/RBUtilities.php';
require_once './lib/AccountManager.php';

header("Content-type: application/json");

$response = array("status" => "ok", "message" => "Operazione completata");

$area = "";
switch ($_POST['area']){
	case 1:
		$area = "parent";
		break;
	case 2:
		$area = "student";
		break;
	case 3:
		$area = "simple_school";
		break;
}

if (!isset($_POST['action']) || $_POST['action'] == 'sendmail'){
	if (!check_mail($_POST['email'])){
		$response['status'] = "olduser";
		$response['message'] = "Non hai inserito una email valida";
		echo json_encode($response);
		exit;
	}
	/*
	 * get the user id
	 */
	try{
		$uid = $db->executeCount("SELECT uid FROM rb_utenti WHERE username = '".$_POST['email']."'");
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage()." === ".$ex->getQuery();
		echo json_encode($response);
		exit;
	}
	if ($uid == null){
		$response['status'] = "nomail";
		$response['message'] = "L'email inserita non e` presente in archivio: ricontrolla o rivolgiti all'amministratore";
		echo json_encode($response);
		exit;
	}
	else {
		try{
			$rb = RBUtilities::getInstance($db);
			$user = $rb->loadUserFromUid($uid, $area);
			$am = new AccountManager($user, new MySQLDataLoader($db));
			$am->recoveryPasswordViaEmail();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage()." === ".$ex->getQuery();
			echo json_encode($response);
			exit;
		}
	}
}
else if ($_POST['action'] == "change"){
	$uid = $_POST['uid'];
	$new_pwd = $_POST['new_pwd'];
	$area = $_POST['area'];
	try{
		$rb = RBUtilities::getInstance($db);
		$user = $rb->loadUserFromUid($uid, $area);
		$am = new AccountManager($user, new MySQLDataLoader($db));
		$am->changePassword($new_pwd);
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage()." === ".$ex->getQuery();
		echo json_encode($response);
		exit;
	}
}

echo json_encode($response);
exit;
