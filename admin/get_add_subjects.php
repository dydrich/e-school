<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

if (!is_numeric($_REQUEST['cls'])) {
	$response['status'] = "ko";
	$response['message'] = "La classe (".$_REQUEST['cls'].") che si vuole modificare non esiste in archivio";
	$res = json_encode($response);
	echo $res;
	exit;
}
$cls = $_REQUEST['cls'];
$year = $_SESSION['__current_year__']->get_ID();
$tipologia = $db->executeCount("SELECT ordine_di_scuola FROM rb_classi WHERE id_classe = {$cls}");
$subject_params = " AND tipologia_scuola = {$tipologia}";
$materie = array();
try {
	if ("cdc" == $_POST['source']) {
		$res_mat = $db->executeQuery("SELECT id_materia, materia FROM rb_materie WHERE has_sons = 0 AND id_materia > 2 {$subject_params}");
		$res_del = $db->executeQuery("SELECT id_materia FROM rb_cdc WHERE id_anno = {$year} AND id_classe = {$cls}");
	}
	else if ("scr" == $_POST['source']) {
		$res_mat = $db->executeQuery("SELECT id_materia, materia FROM rb_materie WHERE id_materia > 2 {$subject_params}");
		$res_del = $db->executeQuery("SELECT DISTINCT(materia) AS id_materia FROM rb_scrutini WHERE anno = {$year} AND classe = {$cls} AND quadrimestre = ".$_POST['quadrimestre']);
	}
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['query'] = $ex->getQuery();
	$response['dbg_message'] = $ex->getMessage();
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
while ($mt = $res_mat->fetch_assoc()) {
	$materie[$mt['id_materia']] = array("id_materia" => $mt['id_materia'], "materia" => truncateString($mt['materia'], 25));
}
while ($cdc = $res_del->fetch_assoc()) {
	if (isset($materie[$cdc['id_materia']])) {
		unset($materie[$cdc['id_materia']]);
	}
}

$response['data'] = $materie;
$res = json_encode($response);
echo $res;
