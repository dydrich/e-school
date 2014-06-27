<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$admin_level = getAdminLevel($_SESSION['__user__']);

$year = $_SESSION['__current_year__']->get_ID();

$classes_table = "rb_classi";
$subject_params = "";
if($_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$subject_params = " AND tipologia_scuola = ".$_SESSION['__school_order__'];
	$scr_classes = "AND rb_classi.id_classe IN (SELECT id_classe FROM {$classes_table})";
}
else if($_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$subject_params = " AND tipologia_scuola = ".$_GET['school_order'];
	$scr_classes = "AND rb_classi.id_classe IN (SELECT id_classe FROM {$classes_table})";
}

/*
 * controllo preliminare di integrita` dei dati
 * se sono presenti record relativi a classi che non si trovano nella tabella delle classi,
 * siamo in presenza di classi cancellate e i record relativi nella tabella cdc vanno eliminati
 */
/*
$sel_to_delete = "SELECT DISTINCT(id_classe) FROM rb_cdc WHERE id_anno = {$year} AND id_classe NOT IN (SELECT id_classe FROM {$classes_table})";
try {
	$res_to_delete = $db->executeQuery($sel_to_delete);
	if ($res_to_delete->num_rows > 0) {
		$to_delete = array();
		while ($row = $res_to_delete->fetch_assoc()) {
			$to_delete[] = $row['id_classe'];
		}
		$db->executeUpdate("DELETE FROM rb_cdc WHERE id_anno = {$year} AND id_classe IN (".join(",", $to_delete).")");
	}
} catch (MySQLException $ex) {
	$ex->redirect();
}
*/

$cls = array();
$materie = array();
$materie_no_cdc = array();
try {
	$res_cls = $db->executeQuery("SELECT id_classe, anno_corso, sezione FROM {$classes_table} WHERE anno_scolastico = {$year} ORDER BY sezione, anno_corso");
	$res_cdc = $db->executeQuery("SELECT rb_cdc.id_classe, rb_cdc.id_materia FROM rb_cdc, rb_classi WHERE rb_cdc.id_classe = rb_classi.id_classe {$scr_classes} AND id_anno = {$year} ORDER BY id_classe");
	$res_mat = $db->executeQuery("SELECT id_materia, materia FROM rb_materie WHERE has_sons = 0 AND id_materia > 2 {$subject_params}");
} catch (MySQLException $ex) {
	$ex->redirect();
}
if($res_cls->num_rows > 0) {
	while ($cl = $res_cls->fetch_assoc()) {
		$cl['cdc'] = array();
		$cls[$cl['id_classe']] = $cl;
	}
}
while ($m = $res_mat->fetch_assoc()) {
	$materie[$m['id_materia']] = $m['materia'];
	$materie_no_cdc[] = $m['id_materia'];
}

while ($_cdc = $res_cdc->fetch_assoc()) {
	$cls[$_cdc['id_classe']]['cdc'][] = $_cdc['id_materia'];
	if (($c = array_search($_cdc['id_materia'], $materie_no_cdc)) !== false) {
		unset($materie_no_cdc[$c]);
	} 
}

$navigation_label = "Area amministrazione: gestione tabella CDC";

include "cdc_state.html.php";