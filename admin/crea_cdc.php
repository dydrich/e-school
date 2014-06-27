<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: application/json");
$response = array("status" => "ok");

$action = "insert";
if (isset($_REQUEST['action']) && $_REQUEST['action']) {
	$action = $_REQUEST['action'];
}

if ($_REQUEST['school_order'] == 1){
	$sostegno = 27;
	$comportamento = 2;
}
else if ($_REQUEST['school_order'] == 2){
	$sostegno = 41;
	$comportamento = 40;
}

$selected_class = $class_to_update = "";
if ($action == "cl_delete" || $action == "cl_reinsert" || $action == "cl_ins_subject" || $action == "cl_del_subject") {
	if (!is_numeric($_REQUEST['cls'])) {
		$response['status'] = "ko";
		$response['message'] = "la classe che si vuole modificare non esiste in archivio;".$_REQUEST['cls'];
		$res = json_encode($response);
		echo $res;
		exit;
	}
	$selected_class = "WHERE id_classe = ".$_REQUEST['cls'];
	$class_to_update = "AND id_classe = ".$_REQUEST['cls'];
}
else if ($_REQUEST['school_order']){
	$selected_class = "WHERE ordine_di_scuola = {$_REQUEST['school_order']}";
	$class_to_update = "AND ordine_di_scuola = {$_REQUEST['school_order']}";
}
$sel_classi = "SELECT id_classe, musicale, sede, ordine_di_scuola FROM rb_classi $selected_class ORDER BY id_classe";
try{
	$res_classi = $db->executeQuery($sel_classi);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

$anno = $_SESSION['__current_year__']->get_ID();

if ($action == "delete" || $action == "reinsert" || $action == "del_subject" || $action == "cl_del_subject" || $action == "cl_delete" || $action == "cl_reinsert") {
	$subject = "";
	if ($action == "del_subject" || $action == "cl_del_subject") {
		if (!is_numeric($_REQUEST['subject'])) {
			$response['status'] = "ko";
			$response['message'] = "la materia che si  vuole cancellare non esiste in archivio;".$_REQUEST['subject'];
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$subject = "AND id_materia = ".$_REQUEST['subject'];
	}
	try{
		$sel = "DELETE FROM rb_cdc USING rb_classi JOIN rb_cdc WHERE rb_cdc.id_classe = rb_classi.id_classe AND id_anno = {$anno} $subject $class_to_update";
		$db->executeUpdate($sel);
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
		$response['message'] = "Errore nella registrazione dei dati";
		$res = json_encode($response);
		echo $res;
		exit;
	}
}
if ($action == "insert" || $action == "reinsert" || $action == "ins_subject" || $action == "cl_ins_subject" || $action == "cl_reinsert") {
	$subject = "";
	if ($action == "ins_subject" || $action == "cl_ins_subject") {
		if (!is_numeric($_REQUEST['subject'])) {
			$response['status'] = "ko";
			$response['message'] = "la materia che si  vuole inserire non esiste in archivio;".$_REQUEST['subject'];
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$subject = "AND id_materia = ".$_REQUEST['subject'];
	}
	while($classe = $res_classi->fetch_assoc()){
		$tipologia = $classe['ordine_di_scuola'];
		
		$param = "AND has_sons = 0 AND (idpadre <> 13 OR idpadre IS NULL)";
		if($classe['musicale'] == 1){
			$param = "AND has_sons = 0 ";
		}
	    $sel_materie = "SELECT id_materia FROM rb_materie WHERE id_materia > 2 AND id_materia <> 27 AND id_materia <> 40 AND id_materia <> 41 AND tipologia_scuola = {$tipologia} $param $subject ORDER BY id_materia";
	    $res_materie = $db->executeQuery($sel_materie);
	    while($mat = $res_materie->fetch_assoc()){
	        $ins = "INSERT INTO rb_cdc (id_anno, id_classe, id_docente, id_materia) VALUES ($anno, ".$classe['id_classe'].", NULL, ".$mat['id_materia'].")";
	        try{
	        	$rs = $db->executeUpdate($ins);
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
$response['message'] = $msg;
$res = json_encode($response);
echo $res;
exit;