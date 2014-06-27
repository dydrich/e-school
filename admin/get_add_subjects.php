<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: text/plain");

if (!is_numeric($_REQUEST['cls'])) {
	echo "ko;la classe che si vuole modificare non esiste in archivio;".$_REQUEST['cls'];
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
	echo "kosql;".$ex->getMessage().";".$ex->getQuery();
	exit;
}
while ($mt = $res_mat->fetch_assoc()) {
	$materie[$mt['id_materia']] = truncateString($mt['materia'], 25);
}
while ($cdc = $res_del->fetch_assoc()) {
	if ($materie[$cdc['id_materia']]) {
		unset($materie[$cdc['id_materia']]);
	}
}
$out = array();
foreach ($materie as $k => $v) {
	$out[] = $k."|".$v;
}

echo "ok;".join("#", $out);
exit;