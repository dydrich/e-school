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
$response = array("status" => "ok");

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
/*
$selected_class = $class_to_update = "";
if ($action == "cl_reinsert" || $action == "cl_ins_subject" || $action == "cl_del_subject") {
	if (!is_numeric($_REQUEST['cls'])) {
		$response['status'] = "ko";
		$response['message'] = "la classe che si vuole modificare non esiste in archivio;".$_REQUEST['cls'];
		$res = json_encode($response);
		echo $res;
		exit;
	}
	$selected_class = "AND rb_alunni.id_classe = ".$_REQUEST['cls'];
	$class_to_update = "AND classe = ".$_REQUEST['cls'];
}

$student_param = $delete_student_param = "";
if ("add_student" == $action) {
	$student = $_REQUEST['student'];
	$student_param = " AND id_alunno = ".$student;
}
else if ("del_student" == $action) {
	$student = $_REQUEST['student'];
	$delete_student_param = " AND alunno = ".$student;
}
else if ("reinsert_student" == $action) {
	$student = $_REQUEST['student'];
	$student_param = " AND id_alunno = ".$student;
	$delete_student_param = " AND alunno = ".$student;
}

$sel_alunni = "SELECT id_alunno, rb_alunni.id_classe, musicale, CONCAT(anno_corso, sezione) AS desc_cls, ordine_di_scuola FROM rb_alunni, {$classes_table} WHERE attivo = '1' $selected_class $student_param AND rb_alunni.id_classe = {$classes_table}.id_classe";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

if($action == "reinsert" || $action == "del_subject" || "cl_reinsert" == $action || "cl_del_subject" == $action || "del_student" == $action || "reinsert_student" == $action) {
	$subject = "";
	if ($action == "del_subject" || $action == "cl_del_subject") {
		if (!is_numeric($_REQUEST['subject'])) {
			$response['status'] = "ko";
			$response['message'] = "la materia che si vuole cancellare non esiste in archivio;".$_REQUEST['cls'];
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$subject = "AND materia = ".$_REQUEST['subject'];
	}
	try{
		$r_del = $db->executeUpdate("DELETE FROM rb_scrutini WHERE anno = {$anno} AND quadrimestre = {$quadrimestre} AND classe IN (SELECT id_classe FROM {$classes_table}) $subject $class_to_update $delete_student_param");
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
		$response['message'] = "Errore nella registrazione dei dati";
		$res = json_encode($response);
		echo $res;
		exit;
	}
}
$materie = array();
if ($action == "insert" || $action == "reinsert" || $action == "ins_subject" || "cl_reinsert" == $action || "cl_ins_subject" == $action || "add_student" == $action || "reinsert_student" == $action) {
	$subject = "";
	if ($action == "ins_subject" || $action == "cl_ins_subject") {
		if (!is_numeric($_REQUEST['subject'])) {
			$response['status'] = "ko";
			$response['message'] = "la materia che si vuole inserire non esiste in archivio;".$_REQUEST['cls'];
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$sel_materie = "SELECT id_materia, tipologia_scuola FROM rb_materie WHERE id_materia = ".$_REQUEST['subject'];
		try{
			$res_materie = $db->executeQuery($sel_materie);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		while($materia = $res_materie->fetch_assoc()){
			$materie[] = array("id_materia" => $materia['id_materia'], "tipologia_scuola" => $materia['tipologia_scuola']);
		}
	}
	else {
		$sel_materie = "SELECT id_materia, tipologia_scuola FROM rb_materie WHERE pagella = 1 {$subject_params} ORDER BY id_materia";
		try{
			$res_materie = $db->executeQuery($sel_materie);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		while($materia = $res_materie->fetch_assoc()){
			$materie[] = array("id_materia" => $materia['id_materia'], "tipologia_scuola" => $materia['tipologia_scuola']);
		}
	}

	$pub = 0;
	try{
		$pub = $db->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$anno} AND quadrimestre = {$quadrimestre}");
		if ($pub == null){
			// inserisco pubblicazione
			$pub = $db->executeUpdate("INSERT INTO rb_pubblicazione_pagelle (anno, quadrimestre) VALUES ({$anno}, {$quadrimestre})");
		}
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
		$response['message'] = "Errore nella registrazione dei dati";
		$res = json_encode($response);
		echo $res;
		exit;
	}

	while($alunno = $res_alunni->fetch_assoc()){
		$id_alunno = $alunno['id_alunno'];
		$classe = $alunno['id_classe'];
		$desc_classe = $alunno['desc_cls'];
		if ($action == "insert" || $action == "reinsert" || "cl_reinsert" == $action || "reinsert_student" == $action) {
			try{
				$db->executeUpdate("INSERT INTO rb_pagelle (id_pubblicazione, id_alunno, id_classe, desc_classe) VALUES ({$pub}, {$id_alunno}, {$classe}, '{$desc_classe}')");
			} catch (MySQLException $ex){
				$response['status'] = "kosql";
				$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
				$response['message'] = "Errore nella registrazione dei dati";
				$res = json_encode($response);
				echo $res;
				exit;
			}
		}

		foreach($materie as $materia){
			if (($alunno['musicale'] != 1) && ($materia['id_materia'] == 13)) {
				continue;
			}
			else if($alunno['ordine_di_scuola'] != $materia['tipologia_scuola']){
				continue;
			}
			$ins = "INSERT INTO rb_scrutini (alunno, classe, anno, quadrimestre, materia) VALUES ($id_alunno, $classe, $anno, $quadrimestre, {$materia['id_materia']})";
			try{
				$r_ins = $db->executeUpdate($ins);
			} catch (MySQLException $ex){
				$response['status'] = "kosql";
				$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
				$response['message'] = "Errore nella registrazione dei dati";
				$res = json_encode($response);
				echo $res;
				exit;
			}
		}
	}
}
*/
$response['status'] = "ok";
$response['message'] = "Operazione completata";
$res = json_encode($response);
echo $res;
exit;
