<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/07/14
 * Time: 14.58
 *
 * gestione supplenze: home page
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$sel_supplenze = "SELECT rb_supplenze.*, classe, anno_corso, sezione FROM rb_supplenze, rb_classi_supplenza, rb_classi WHERE rb_supplenze.id_supplenza = rb_classi_supplenza.id_supplenza AND anno = {$_SESSION['__current_year__']->get_ID()} AND rb_supplenze.ordine_di_scuola = {$_SESSION['__school_order__']} AND id_classe = classe ORDER BY data_fine_supplenza DESC";
try {
	$res_supplenze = $db->executeQuery($sel_supplenze);
} catch (MySQLException $ex){
	$ex->redirect();
}
$supplenze = array();
$supplenze_concluse = array();
$supplenze_in_corso = array();

while ($row = $res_supplenze->fetch_assoc()) {
	if (!isset($supplenze[$row['id_supplenza']])) {
		$row['tit'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$row['id_docente_assente']}");
		$row['sup'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$row['id_supplente']}");
		$supplenze[$row['id_supplenza']] = $row;
		$supplenze[$row['id_supplenza']]['classi'] = array();
		if ($row['data_fine_supplenza'] >= date("Y-m-d")) {
			$supplenze_in_corso[$row['id_supplenza']] = $row;
			$supplenze_in_corso[$row['id_supplenza']]['classi'] = array();
		}
		else {
			$supplenze_concluse[$row['id_supplenza']] = $row;
			$supplenze_concluse[$row['id_supplenza']]['classi'] = array();
		}
	}
	$supplenze[$row['id_supplenza']]['classi'][$row['classe']] = $row['anno_corso'].$row['sezione'];
	if ($row['data_fine_supplenza'] >= date("Y-m-d")) {
		$supplenze_in_corso[$row['id_supplenza']]['classi'][$row['classe']] = $row['anno_corso'].$row['sezione'];
	}
	else {
		$supplenze_concluse[$row['id_supplenza']]['classi'][$row['classe']] = $row['anno_corso'].$row['sezione'];
	}
}

include "supplenze.html.php";
