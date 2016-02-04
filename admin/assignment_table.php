<?php

/*
tabella scrutini
*/

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

$school_orders = ["1" => "scuola media", "2" => "scuola primaria", "3" => "scuola dell'infanzia"];

$classes_table = "rb_classi";
$subject_params = "";
$scr_classes = "";
$school_order = "";
if(isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$subject_params = " AND tipologia_scuola = ".$_GET['school_order'];
	$scr_classes = "AND classe IN (SELECT id_classe FROM {$classes_table})";
	$school_order = $_GET['school_order'];
}
else if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$subject_params = " AND tipologia_scuola = ".$_SESSION['__school_order__'];
	$scr_classes = "AND classe IN (SELECT id_classe FROM {$classes_table})";
	$school_order = $_SESSION['__school_order__'];
}

$cls = array();
$materie = array();
$materie_no_scr = array();
$materie_scr = array();
try {
	$res_cls = $db->executeQuery("SELECT id_classe, anno_corso, sezione, nome FROM {$classes_table}, rb_sedi WHERE sede = id_sede AND anno_scolastico = {$year} ORDER BY {$classes_table}.ordine_di_scuola, sezione, anno_corso");
	$res_scr = $db->executeQuery("SELECT classe, materia FROM rb_scrutini WHERE anno = {$year} AND quadrimestre = {$_REQUEST['quadrimestre']} {$scr_classes} GROUP BY classe, materia ORDER BY classe");
	$res_mat = $db->executeQuery("SELECT id_materia, materia, pagella, tipologia_scuola FROM rb_materie WHERE id_materia <> 1 {$subject_params}");
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

$check_data = "SELECT COUNT(id) FROM rb_scrutini, rb_classi WHERE classe = id_classe AND ordine_di_scuola = $school_order AND anno = $year AND quadrimestre = {$_REQUEST['quadrimestre']}";
$count_data = $db->executeCount($check_data);
$inserted_data = 0;
$class_data = [];
foreach ($cls as $k => $cl) {
	$class_data[$k] = $db->executeCount("SELECT COUNT(id) FROM rb_scrutini, rb_classi WHERE classe = id_classe AND ordine_di_scuola = $school_order AND anno = $year AND quadrimestre = {$_REQUEST['quadrimestre']} AND voto IS NOT NULL AND classe = $k");
	$inserted_data += $class_data[$k];
}
if ($count_data > 0) {
	//$inserted_data = $db->executeCount("SELECT COUNT(id) FROM rb_scrutini, rb_classi WHERE classe = id_classe AND ordine_di_scuola = $school_order AND anno = $year AND quadrimestre = {$_REQUEST['quadrimestre']} AND voto IS NOT NULL");
}

$navigation_label = "gestione scrutini";
$drawer_label = "Gestione tabella scrutini ".$school_orders[$school_order]." - ".$_REQUEST['quadrimestre']." quadrimestre";

include "assignment_table.html.php";
