<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

$cls = $_POST['cls'];
if(!is_numeric($cls)){
	$response['status'] = "ko";
	$response['message'] = "Classe non esistente";
	$res = json_encode($response);
	echo $res;
	exit;
}
if($_POST['action'] == "student_insert"){
	$sel_sts = "SELECT id_alunno, CONCAT_WS(' ', cognome, nome) AS name FROM rb_alunni WHERE id_classe = {$cls} AND attivo = '1' AND id_alunno NOT IN (SELECT DISTINCT id_alunno FROM rb_reg_alunni, rb_reg_classi WHERE id_reg = id_registro AND id_anno = {$_SESSION['__current_year__']->get_ID()} AND rb_reg_alunni.id_classe = {$cls}) ORDER BY cognome, nome";
}
else {
	$sel_sts = "SELECT id_alunno, CONCAT_WS(' ', cognome, nome) AS name FROM rb_alunni WHERE id_classe = {$cls} AND attivo = '1' ORDER BY cognome, nome";
}
try{
	$res_sts = $db->executeQuery($sel_sts);
} catch(MySQLException $ex){
	$response['status'] = "kosql";
	$response['query'] = $ex->getQuery();
	$response['dbg_message'] = $ex->getMessage();
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

$sts = array();

while($row = $res_sts->fetch_assoc()){
	$sts[] = array("id" => $row['id_alunno'], "name" => $row['name']);
}

$response['data'] = $sts;
$res = json_encode($response);
echo $res;
exit;
