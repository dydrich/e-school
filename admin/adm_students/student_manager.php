<?php

require_once "../../lib/StudentManager.php";
require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Password modificata");

$id_anno = $_SESSION['__current_year__']->get_ID();

$ordine_scuola = "";
if($_SESSION['__school_order__'] != 0){
	$ordine_scuola = $_SESSION['__school_order__'];
}
else if($_SESSION['school_order'] != 0){
	$ordine_scuola = $_SESSION['school_order'];
}

$sel_reg = "SELECT COUNT(*) FROM rb_reg_classi WHERE id_anno = $id_anno";
$exist_reg = $db->executeCount($sel_reg);

$check_data1 = "SELECT COUNT(*) FROM rb_scrutini WHERE anno = $id_anno AND quadrimestre = 1";
$exist_rep = $db->executeCount($check_data1);

/*
 * ACTIONS:
 * 1: insert new student
 * 2: delete student
 * 3: update student
 * 4: update student account
 */

$studentBean = null;
$alunno = $_REQUEST['_i'];
if($_REQUEST['action'] == 1 || $_REQUEST['action'] == 3){
	$uname = trim($db->real_escape_string($_POST['uname']));
	$pwd = trim($db->real_escape_string($_POST['pwd']));
	$nome = $db->real_escape_string(trim($_POST['nome']));
	$cognome = trim($db->real_escape_string($_POST['cognome']));
	$data_nascita = format_date($_POST['data_nascita'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$luogo_nascita = $db->real_escape_string($_POST['luogo']);
	$cf = trim($db->real_escape_string($_POST['cf']));
	$sesso = $_POST['sesso'];
	list($id_classe, $classe) = explode(";", $_POST['classe']);
	$old_class = $_REQUEST['old_class'];
	if($ordine_scuola == ""){
		$ordine_scuola = $db->executeCount("SELECT ordine_di_scuola FROM rb_classi WHERE id_classe = {$id_classe}");
	}
	$school_year = $_SESSION['__school_year__'][$ordine_scuola];


	$studentBean = new Student($alunno, $nome, $cognome, 8, 256, $uname);
	$studentBean->setOldClass($old_class);
	$studentBean->setBirthday($data_nascita);
	$studentBean->setCf($cf);
	$studentBean->setSex($sesso);
	$studentBean->setClass($id_classe);
	$studentBean->setBirthPlace($luogo_nascita);
	if($_REQUEST['action'] == 1){
		$studentBean->setPwd($pwd);
	}
}
else if ($_REQUEST['action'] == 2){
	$studentBean = new Student($alunno, "", "", "", "", "");
}
else if($_REQUEST['action'] == 4){
	$uname = trim($db->real_escape_string($_POST['uname']));
	$pwd = trim($db->real_escape_string($_POST['pwd']));
	$studentBean = new Student($alunno, "", "", "", "", $uname);
	$studentBean->setPwd($pwd);
}
$student_manager = new StudentManager($db, $studentBean);
$student_manager->setExistClassbook($exist_reg);
$student_manager->setExistReport($exist_rep);
if($school_year){
	$student_manager->setSchoolYear($school_year);
}

switch($_POST['action']){
	case 1:     // inserimento
		try{
			$db->executeUpdate("BEGIN");
			$student_manager->addStudent();
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$response['message'] = "Alunno inserito";
	break;
	case 2:     // cancellazione
		try{
			$student_manager->deleteStudent();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$response['message'] = "Alunno cancellato";
	break;
	case 3:     // modifica
		try{
			$db->executeUpdate("BEGIN");
			$student_manager->updateStudent();
			if(($_REQUEST['old_class'] != 0) && ($_REQUEST['old_class'] != $id_classe)){
				$student_manager->changeClass();
			}
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$response['message'] = "Alunno modificato";
	break;
	case 4:     // account 
		try{
			$student_manager->updateAccount();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$response['message'] = "Account modificato";
	break;
}
$res = json_encode($response);
echo $res;
exit;
