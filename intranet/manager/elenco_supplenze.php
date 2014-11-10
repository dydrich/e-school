<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/07/14
 * Time: 16.58
 *
 * elenco supplenze
 * status: open o closed
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "";
switch($_SESSION['__school_order__']) {
	case 1:
		$navigation_label .= "scuola secondaria";
		break;
	case 2:
		$navigation_label .= "scuola primaria";
		break;
}

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$status = "all";
$param = "";
$label = "";
if (isset($_REQUEST['status'])) {
	$status = $_REQUEST['status'];
}

if ($status == "open") {
	$param = " AND data_fine_supplenza >= DATE(NOW()) ";
	$label = "in corso";
}
else if ($status == "closed") {
	$param = " AND data_fine_supplenza < DATE(NOW()) ";
	$label = "concluse";
}

$sel_supplenze = "SELECT rb_supplenze.*, classe, anno_corso, sezione FROM rb_supplenze, rb_classi_supplenza, rb_classi WHERE rb_supplenze.id_supplenza = rb_classi_supplenza.id_supplenza AND anno = {$_SESSION['__current_year__']->get_ID()} AND rb_supplenze.ordine_di_scuola = {$_SESSION['__school_order__']} AND id_classe = classe ".$param." ORDER BY data_fine_supplenza DESC";
try {
	$res_supplenze = $db->executeQuery($sel_supplenze);
} catch (MySQLException $ex){
	$ex->redirect();
}
$supplenze = array();

while ($row = $res_supplenze->fetch_assoc()) {
	if (!isset($supplenze[$row['id_supplenza']])) {
		$row['tit'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$row['id_docente_assente']}");
		$row['sup'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$row['id_supplente']}");
		$row['days'] = $db->executeCount("SELECT COUNT(id_reg) FROM rb_reg_classi WHERE id_classe = {$row['classe']} AND (data BETWEEN '{$row['data_inizio_supplenza']}' AND '{$row['data_fine_supplenza']}')");
		$supplenze[$row['id_supplenza']] = $row;
		$supplenze[$row['id_supplenza']]['classi'] = array();
	}
	$supplenze[$row['id_supplenza']]['classi'][$row['classe']] = $row['anno_corso'].$row['sezione'];
}

$row_class = "docs_row";
$row_class_menu = " docs_row_menu";

$drawer_label = "Elenco supplenze ".$label;

include "elenco_supplenze.html.php";
