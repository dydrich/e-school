<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$year = $_SESSION['__current_year__']->get_ID();

$classes_table = "rb_classi";
$school_order = "0";
if($_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school_order = $_SESSION['__school_order__'];
}
else if($_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$school_order = $_GET['school_order'];
}

$school_orders = array("1" => "scuola media", "2" => "scuola primaria", "3" => "scuola dell'infanzia");

$cls = array();
$count_days = 0;
try {
	$res_cls = $db->executeQuery("SELECT {$classes_table}.id_classe, anno_corso, sezione, COUNT(id_alunno) AS c_alunni FROM {$classes_table}, rb_alunni WHERE anno_scolastico = {$year} AND attivo = '1' AND {$classes_table}.id_classe = rb_alunni.id_classe GROUP BY {$classes_table}.id_classe, anno_corso, sezione ORDER BY sezione, anno_corso");
	$count_days = $db->executeCount("SELECT COUNT(DISTINCT data) FROM rb_reg_classi WHERE id_anno = {$year}");
} catch (MySQLException $ex) {
	$ex->redirect();
}
if($res_cls->num_rows > 0) {
	while ($cl = $res_cls->fetch_assoc()) {
		$c_al = $db->executeCount("SELECT COUNT(DISTINCT id_alunno) FROM rb_reg_alunni, rb_reg_classi WHERE id_registro = id_reg AND rb_reg_alunni.id_classe = {$cl['id_classe']} AND id_anno = {$year}");
		$cl['count_reg_stud'] = $c_al;
		$cl['count_reg_rec'] = $db->executeCount("SELECT COUNT(id_alunno) FROM rb_reg_alunni, rb_reg_classi WHERE id_registro = id_reg AND rb_reg_alunni.id_classe = {$cl['id_classe']} AND id_anno = {$year}");
		$cls[$cl['id_classe']] = $cl;
	}
}

$navigation_label = "tabella registro";
$drawer_label = "Gestione tabella registro di classe ". $school_orders[$school_order];

include "classbook_state.html.php";
