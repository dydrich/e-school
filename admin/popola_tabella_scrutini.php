<?php

require_once "../lib/start.php";

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
if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$subject_params = " AND tipologia_scuola = ".$_SESSION['__school_order__'];
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$subject_params = " AND tipologia_scuola = ".$_GET['school_order'];
}

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

$sel_alunni = "SELECT id_alunno, rb_alunni.id_classe, musicale, CONCAT(anno_corso, sezione) AS desc_cls, ordine_di_scuola FROM rb_alunni, {$classes_table} WHERE attivo = '1' $selected_class AND rb_alunni.id_classe = {$classes_table}.id_classe";
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

if($action == "reinsert" || $action == "del_subject" || "cl_reinsert" == $action || "cl_del_subject" == $action) {
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
		$r_del = $db->executeUpdate("DELETE FROM rb_scrutini WHERE anno = {$anno} AND quadrimestre = {$quadrimestre} AND classe IN (SELECT id_classe FROM {$classes_table}) $subject $class_to_update");
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
if ($action == "insert" || $action == "reinsert" || $action == "ins_subject" || "cl_reinsert" == $action || "cl_ins_subject" == $action) {
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
		if ($action == "insert" || $action == "reinsert" || "cl_reinsert" == $action) {
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

$response['status'] = "ok";
$response['message'] = "Operazione completata";
$res = json_encode($response);
echo $res;
exit;
