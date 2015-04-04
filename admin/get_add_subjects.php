<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

$act = $_REQUEST['act'];

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
if ($tipologia == 1){
	$sostegno = 27;
	$comportamento = 2;
}
else if ($tipologia == 2){
	$sostegno = 41;
	$comportamento = 40;
}
$materie = array();
try {
	if ("cdc" == $_POST['source']) {
		$res_mat = $db->executeQuery("SELECT id_materia, materia FROM rb_materie WHERE has_sons = 0 AND id_materia > 2 AND id_materia <> {$sostegno} AND id_materia <> {$comportamento} {$subject_params}");
		$res_del = $db->executeQuery("SELECT rb_materie.id_materia, rb_materie.materia FROM rb_cdc, rb_materie WHERE rb_materie.id_materia = rb_cdc.id_materia AND id_anno = {$year} AND id_classe = {$cls}");
	}
	else if ("scr" == $_POST['source']) {
		$res_mat = $db->executeQuery("SELECT id_materia, materia FROM rb_materie WHERE id_materia > 2 {$subject_params}");
		$res_del = $db->executeQuery("SELECT DISTINCT(rb_scrutini.materia) AS id_materia, rb_materie.materia FROM rb_scrutini, rb_materie WHERE rb_scrutini.materia = id_materia AND anno = {$year} AND classe = {$cls} AND quadrimestre = ".$_POST['quadrimestre']);
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
if ($act == "add") {
	while ($mt = $res_mat->fetch_assoc()) {
		$materie[$mt['id_materia']] = array("id_materia" => $mt['id_materia'], "materia" => truncateString($mt['materia'], 25));
	}
	while ($cdc = $res_del->fetch_assoc()) {
		if (isset($materie[$cdc['id_materia']])) {
			unset($materie[$cdc['id_materia']]);
		}
	}
}
else {
	while ($mt = $res_del->fetch_assoc()) {
		$materie[$mt['id_materia']] = array("id_materia" => $mt['id_materia'], "materia" => truncateString($mt['materia'], 25));
	}
}

$response['data'] = $materie;
$res = json_encode($response);
echo $res;
