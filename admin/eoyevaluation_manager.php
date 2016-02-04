<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 05/04/15
 * Time: 20.42
 *
 * end of year evaluation manager
 *
 */

require_once "../lib/start.php";
require_once '../lib/EndOfYearEvaluationManager.php';

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: application/json");
$response = ["status" => "ok"];

$action = $_POST['action'];
$year = $_SESSION['__current_year__'];
$anno = $year->get_ID();
$quadrimestre = $_REQUEST['quadrimestre'];

$classes_table = "rb_classi";
$subject_params = "";
if(isset($_REQUEST['school_order']) && $_REQUEST['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_REQUEST['school_order']}";
	$subject_params = " AND tipologia_scuola = ".$_REQUEST['school_order'];
}
else if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$subject_params = " AND tipologia_scuola = ".$_SESSION['__school_order__'];
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
	$subject_params = " AND tipologia_scuola = ".$_SESSION['school_order'];
}

$eoyem = new \eschool\EndOfYearEvaluationManager(new MySQLDataLoader($db), $anno, $quadrimestre, $_REQUEST['school_order']);

$subject = $cls = $student = 0;
if (isset($_REQUEST['subject'])) {
	$subject = $_REQUEST['subject'];
}
if (isset($_REQUEST['cls'])) {
	$cls = $_REQUEST['cls'];
}
if (isset($_REQUEST['student'])) {
	$student = $_REQUEST['student'];
}
if (!is_numeric($cls)) {
	$response['status'] = "ko";
	$response['message'] = "la classe che si vuole modificare non esiste in archivio;".$cls;
	$res = json_encode($response);
	echo $res;
	exit;
}
if (!is_numeric($subject)) {
	$response['status'] = "ko";
	$response['message'] = "la materia indicata non esiste in archivio;".$subject;
	$res = json_encode($response);
	echo $res;
	exit;
}
if (!is_numeric($student)) {
	$response['status'] = "ko";
	$response['message'] = "lo studente indicato non esiste in archivio;".$student;
	$res = json_encode($response);
	echo $res;
	exit;
}

switch ($action) {
	case "fix":
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$GLOBAL_SCOPE);
		try {
			$eoyem->fix();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nell'inserimento dei dati";
			$response['action'] = "fix";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "insert":
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$GLOBAL_SCOPE);
		try {
			$eoyem->insert();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nell'inserimento dei dati";
			$response['action'] = "insert";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "delete":
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$GLOBAL_SCOPE);
		try {
			$eoyem->delete();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella cancellazione dei dati";
			$response['action'] = "delete";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "reinsert":
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$GLOBAL_SCOPE);
		try {
			$eoyem->reinsert();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "reinsert";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "ins_subject":
		$subject = $_REQUEST['subject'];
		$eoyem->setSubject($subject);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$GLOBAL_SCOPE);
		try {
			$eoyem->insert();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "ins_subject";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "del_subject":
		$subject = $_REQUEST['subject'];
		$eoyem->setSubject($subject);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$GLOBAL_SCOPE);
		try {
			$eoyem->delete();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "del_subject";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "cl_reinsert":
		$cls = $_REQUEST['cls'];
		$eoyem->setClass($cls);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$CLASS_SCOPE);
		try {
			$eoyem->reinsert();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "cl_reinsert";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "cl_ins_subject":
		$subject = $_REQUEST['subject'];
		$cls = $_REQUEST['cls'];
		$eoyem->setClass($cls);
		$eoyem->setSubject($subject);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$CLASS_SCOPE);
		try {
			$eoyem->insert();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "cl_ins_subject";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "cl_del_subject":
		$subject = $_REQUEST['subject'];
		$cls = $_REQUEST['cls'];
		$eoyem->setClass($cls);
		$eoyem->setSubject($subject);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$CLASS_SCOPE);
		try {
			$eoyem->delete();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "cl_l_subject";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "add_student":
		$student = $_REQUEST['student'];
		$eoyem->setStudent($student);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$STUDENT_SCOPE);
		try {
			$eoyem->insert();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "add_student";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "del_student":
		$student = $_REQUEST['student'];
		$eoyem->setStudent($student);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$STUDENT_SCOPE);
		try {
			$eoyem->delete();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "del_student";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
	case "reinsert_student":
		$student = $_REQUEST['student'];
		$eoyem->setStudent($student);
		$eoyem->setActionScope(\eschool\EndOfYearEvaluationManager::$STUDENT_SCOPE);
		try {
			$eoyem->reinsert();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['action'] = "reinsert_student";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
}

$response['status'] = "ok";
$response['message'] = "Operazione completata";
$res = json_encode($response);
echo $res;
exit;
