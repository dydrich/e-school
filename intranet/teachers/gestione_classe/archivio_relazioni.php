<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/8/16
 * Time: 11:22 AM
 * archivio relazioni
 */
require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$navigation_label = "gestione classe";
$drawer_label = "Archivio relazioni";
$page = getFileName();

require_once "../reload_class_in_session.php";

$school = $_SESSION['__user__']->getSchoolOrder();
$_SESSION['__school_order__'] = $school;

$year = $_SESSION['__current_year__']->get_ID();

$sel_anni = "SELECT id_anno, descrizione FROM rb_anni WHERE id_anno < $year ORDER BY id_anno DESC";
try{
	$res_anni = $db->executeQuery($sel_anni);
} catch(MySQLException $ex){
	$ex->redirect();
}
$anni_corso_classe = array();
while ($row = $res_anni->fetch_assoc()) {
	$anni_corso_classe[] = $row;
}

$anno_corso = $_SESSION['__classe__']->get_anno();
$sezione = $_SESSION['__classe__']->get_sezione();

$anno_sel = isset($_REQUEST['sel']) ? $_REQUEST['sel'] : 1;

if (isset($_REQUEST['y']) && $_REQUEST['y'] != "") {
	if (isset($_REQUEST['sel'])) {
		$index = abs($anno_sel - 2);
	}
	else {
		$index = 0;
	}
	$sel_rel = "SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, rb_relazioni_docente.id AS rel_id, classe, owner, categoria 
				FROM rb_documents, rb_relazioni_docente 
				WHERE rb_documents.id = id_documento 
				AND tipo_documento <> 6 
				AND anno_scolastico = {$_REQUEST['y']} 
				AND classe = {$_SESSION['__classe__']->get_ID()} 
				ORDER BY tipo_documento, data_upload DESC";
	$sel_cdc = "SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, rb_documenti_cdc.id AS rel_id, classe, owner, categoria 
				FROM rb_documents, rb_documenti_cdc 
				WHERE rb_documents.id = id_documento 
				AND anno_scolastico = {$_REQUEST['y']} 
				AND classe = {$_SESSION['__classe__']->get_ID()} 
				ORDER BY tipo_documento, data_upload DESC";
	try {
		$res_cdc = $db->executeQuery($sel_cdc);
	} catch (MySQLException $ex) {
		$ex->redirect();
	}

	$relazioni = array();
	if (isset($res_cdc)) {
		while ($row = $res_cdc->fetch_assoc()) {
			if (!isset($relazioni[$row['doc_id']])) {
				if ($row['categoria'] > 3) {
					$relazioni[$row['doc_id']] = array("id_documento" => $row['doc_id'], "tipo" => $row['doc_type'], "data" => $row['data_upload'], "file" => $row['file'], "titolo" => substr($row['titolo'], 0, strlen($row['titolo']) - 11), "id_relazione" => $row['rel_id'], "classe" => $row['classe'], "owner" => $row['owner']);
				}
				else {
					$relazioni[$row['doc_id']] = array("id_documento" => $row['doc_id'], "tipo" => $row['doc_type'], "data" => $row['data_upload'], "file" => $row['file'], "titolo" => substr($row['titolo'], 0, strlen($row['titolo']) - 2), "id_relazione" => $row['rel_id'], "classe" => $row['classe'], "owner" => $row['owner']);
				}
			}
		}
	}
	$res_rel = $db->executeQuery($sel_rel);
	while ($row = $res_rel->fetch_assoc()) {
		if (!isset($relazioni[$row['doc_id']])) {
			$relazioni[$row['doc_id']] = array("id_documento" => $row['doc_id'], "tipo" => $row['doc_type'], "data" => $row['data_upload'], "file" => $row['file'], "titolo" => substr($row['titolo'], 0, strlen($row['titolo']) - 12), "id_relazione" => $row['rel_id'], "classe" => $row['classe'], "owner" => $row['owner']);
		}
	}
	$_SESSION['relazioni'] = $relazioni;

	$drawer_label .= " a. s. ".$anni_corso_classe[$index]['descrizione'];

	include "archivio_relazioni.html.php";
}
else {
	$ac2 = $anno_corso - 1;
	$ac1 = $anno_corso - 2;
	$idc2 = $anni_corso_classe[0]['id_anno'];
	$desc2 = $anni_corso_classe[0]['descrizione'];
	if ($ac1 > 0) {
		$idc1 = $anni_corso_classe[1]['id_anno'];
		$desc1 = $anni_corso_classe[1]['descrizione'];
	}
	include "scegli_archivio_relazioni.html.php";
}