<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 19/08/15
 * Time: 7.03
 * relazioni docente
 * se all=1 (coordinatore) mostra tutte le relazione della classe, altrimenti solo quelle del docente
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$page = getFileName();

if (isset($_REQUEST['cls'])) {
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if ($_REQUEST['all'] == 0) {
	$sel_rel = "SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, rb_relazioni_docente.id AS rel_id, classe, owner FROM rb_documents, rb_relazioni_docente WHERE rb_documents.id = id_documento AND tipo_documento <> 6 AND anno_scolastico = {$_SESSION['__current_year__']->get_ID()} AND owner = {$_SESSION['__user__']->getUid()} AND classe = {$_SESSION['__classe__']->get_ID()} ORDER BY tipo_documento, data_upload DESC";
}
else {
	if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getUsername() != "rbachis") && $_SESSION['__user__']->getSchoolOrder() != 2 ){
		header("Location: relazioni.php?all=0");
	}
	$sel_rel = "SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, rb_relazioni_docente.id AS rel_id, classe, owner, categoria FROM rb_documents, rb_relazioni_docente WHERE rb_documents.id = id_documento AND tipo_documento <> 6 AND anno_scolastico = {$_SESSION['__current_year__']->get_ID()} AND classe = {$_SESSION['__classe__']->get_ID()} ORDER BY tipo_documento, data_upload DESC";
	$sel_cdc = "SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, rb_documenti_cdc.id AS rel_id, classe, owner, categoria FROM rb_documents, rb_documenti_cdc WHERE rb_documents.id = id_documento AND anno_scolastico = {$_SESSION['__current_year__']->get_ID()} AND classe = {$_SESSION['__classe__']->get_ID()} ORDER BY tipo_documento, data_upload DESC";
	try {
		$res_cdc = $db->executeQuery($sel_cdc);
	} catch (MySQLException $ex) {
		$ex->redirect();
	}
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

$navigation_label = "gestione classe";
$drawer_label = "Elenco relazioni";

include "relazioni.html.php";
