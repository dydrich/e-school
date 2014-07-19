<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 19/07/14
 * Time: 16.52
 * restituisce il cdc di una classe passata come parametro, in formato json
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|AMS_PERM|APS_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$cls = $_REQUEST['cls'];
$cdc = array("segretario" => array(), "coordinatore" => array());
$anno = $_SESSION['__current_year__']->get_ID();

$sel_seg = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti, rb_cdc WHERE uid = rb_docenti.id_docente AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_classe = {$cls} AND rb_cdc.id_anno = {$anno} GROUP BY uid, nome, cognome, coordinatore ORDER BY cognome, nome";
$sel_coord = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti, rb_classi WHERE uid = rb_docenti.id_docente AND tipologia_scuola = ordine_di_scuola AND id_classe = {$cls} ORDER BY cognome, nome";
$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE id_classe = {$cls} AND sede = id_sede";
try{
	$res_seg = $db->executeQuery($sel_seg);
	$res_coord = $db->executeQuery($sel_coord);
	$res_classe = $db->executeQuery($sel_classe);
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLBACK");
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}
$classe = $res_classe->fetch_assoc();
while ($row = $res_seg->fetch_assoc()){
	$cdc["segretario"][] = array('uid' => $row['uid'], 'cognome' => $row['cognome'], 'nome' => $row['nome']);
}
while ($row = $res_coord->fetch_assoc()){
	$cdc["coordinatore"][] = array('uid' => $row['uid'], 'cognome' => $row['cognome'], 'nome' => $row['nome']);
}
$coordinatore = $segretario = 0;
if ($classe['coordinatore'] != ""){
	$coordinatore = $classe['coordinatore'];
}
if ($classe['segretario'] != ""){
	$segretario = $classe['segretario'];
}

$response['data'] = $cdc;
$response['cls'] = array("classe" => $classe['anno_corso'].$classe['sezione'], "coordinatore" => $coordinatore, "segretario" => $segretario);
echo json_encode($response);
exit;