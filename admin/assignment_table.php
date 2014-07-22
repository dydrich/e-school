<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$year = $_SESSION['__current_year__']->get_ID();
if (!is_numeric($_REQUEST['quadrimestre'])) {
	print "ko;il quadrimestre che si vuole modificare non esiste in archivio;".$_REQUEST['quadrimestre'];
	exit;
}

$school_orders = array("1" => "scuola media", "2" => "scuola primaria", "3" => "scuola dell'infanzia");

$classes_table = "rb_classi";
$subject_params = "";
$scr_classes = "";
if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$subject_params = " AND tipologia_scuola = ".$_SESSION['__school_order__'];
	$scr_classes = "AND classe IN (SELECT id_classe FROM {$classes_table})";
}
else if(isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$subject_params = " AND tipologia_scuola = ".$_GET['school_order'];
	$scr_classes = "AND classe IN (SELECT id_classe FROM {$classes_table})";
}

/*
 * controllo preliminare di integrita` dei dati
* se sono presenti record relativi a classi che non si trovano nella tabella delle classi,
* siamo in presenza di classi cancellate e i record relativi nella tabella scrutini vanno eliminati
*/
$sel_to_delete = "SELECT DISTINCT(classe) FROM rb_scrutini WHERE anno = {$year} AND quadrimestre = {$_REQUEST['quadrimestre']} AND classe NOT IN (SELECT id_classe FROM {$classes_table})";
try {
	$res_to_delete = $db->executeQuery($sel_to_delete);
	if ($res_to_delete->num_rows > 0) {
		$to_delete = array();
		while ($row = $res_to_delete->fetch_assoc()) {
			$to_delete[] = $row['classe'];
		}
		$db->executeUpdate("DELETE FROM rb_scrutini WHERE anno = {$year} AND quadrimestre = {$_REQUEST['quadrimestre']} AND classe IN (".join(",", $to_delete).")");
	}
} catch (MySQLException $ex) {
	$ex->redirect();
}

$cls = array();
$materie = array();
$materie_no_scr = array();
$materie_scr = array();
try {
	$res_cls = $db->executeQuery("SELECT id_classe, anno_corso, sezione, nome FROM {$classes_table}, rb_sedi WHERE sede = id_sede AND anno_scolastico = {$year} ORDER BY sezione, anno_corso");
	$res_scr = $db->executeQuery("SELECT classe, materia FROM rb_scrutini WHERE anno = {$year} AND quadrimestre = {$_REQUEST['quadrimestre']} {$scr_classes} GROUP BY classe, materia ORDER BY classe");
	$res_mat = $db->executeQuery("SELECT id_materia, materia, pagella FROM rb_materie WHERE id_materia <> 1 {$subject_params}");
} catch (MySQLException $ex) {
	$ex->redirect();
}
if($res_cls->num_rows > 0) {
	while ($cl = $res_cls->fetch_assoc()) {
		$cl['scr'] = array();
		$cls[$cl['id_classe']] = $cl;
	}
}
while ($m = $res_mat->fetch_assoc()) {
	$materie[$m['id_materia']] = $m['materia'];
	$materie_no_scr[] = $m['id_materia'];
}

while ($_scr = $res_scr->fetch_assoc()) {
	$cls[$_scr['classe']]['scr'][] = $_scr['materia'];
	if (($c = array_search($_scr['materia'], $materie_no_scr)) !== false) {
		unset($materie_no_scr[$c]);
	}
	if (!isset($materie_scr[$_scr['materia']])) {
		$materie_scr[$_scr['materia']] = $materie[$_scr['materia']];
	}
}

$navigation_label = "Area amministrazione: gestione tabella scrutini";

include "assignment_table.html.php";
