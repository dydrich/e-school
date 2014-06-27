<?php

/*
    modifica la materia in orario nella visualizazione del docente
    e nel database: il lato client usa Ajax per l'update
*/

include "../lib/start.php";

check_session();
check_permission(ADM_PERM|DOC_PERM);

$anno = $_SESSION['__current_year__']->get_ID();

$response = array("status" => "ok", "message" => "");

header("Content-type: application/json");

$teacher = $_SESSION['__user__']->getUid();
if(isset($_REQUEST['getID'])){
	$ora = $_REQUEST['ora'];
	$giorno = $_REQUEST['giorno'];
	$mat = $_REQUEST['materia'];
	$classe = $_REQUEST['classe'];
	$old_class = $_REQUEST['old_class'];
	$del = $_REQUEST['del'];
	if($old_class == "" || $old_class == 0){
		$old_class = $classe;
	}
	$desc = $_REQUEST['desc'];
	$sel_id = "SELECT id FROM rb_orario WHERE giorno = {$giorno} AND ora = {$ora} AND classe = {$old_class} AND anno = {$anno}";
	//print $sel_id;
	try{
		$res_id = $db->executeQuery($sel_id);
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
		$response['message'] = "Errore nella registrazione dei dati";
		$res = json_encode($response);
		echo $res;
		exit;
	}
	$_id = $res_id->fetch_assoc();
	$id_ora = $_id['id'];
	if($_REQUEST['act'] == 1){
		// delete
		$del_h = "UPDATE rb_orario SET materia = 1, docente = NULL, descrizione = NULL WHERE id = {$id_ora}";
		try{
			$r_dl = $db->execute($del_h);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		if($del){
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$sel_new_id = "SELECT id FROM rb_orario WHERE giorno = '{$giorno}' AND ora = {$ora} AND classe = {$classe} AND anno = {$anno}";
		//print $sel_id;
		try{
			$id_ora = $db->executeCount($sel_new_id);
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
else{
	$id_ora = $_POST['uid'];
	$mat = $_POST['mat'];
	$teacher = $_POST['teacher'];
	$desc = "";
	
}

$upd = "UPDATE rb_orario SET materia = {$mat}, docente = {$teacher}, descrizione = ".field_null($desc, true)." WHERE id = {$id_ora}";
try{
	$db->execute($upd);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
$res = "ok";
$sel_m = "SELECT materia FROM rb_materie WHERE id_materia = {$mat}";
$res_m = $db->execute($sel_m);
$m = $res_m->fetch_assoc();

$response['materia'] = $m['materia'];
$response['id_ora'] = $id_ora;
$response['upd'] = $upd;

$res = json_encode($response);
echo $res;
exit;