<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 19/08/15
 * Time: 7.03
 * relazioni di classe (area segreteria)
 */
require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$page = getFileName();

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);
$anno = $_SESSION['__current_year__']->get_ID();
$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$relazioni = array();
$cdc_stmt = $db->prepare("SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, categoria, rb_documenti_cdc.id AS rel_id, classe, owner FROM rb_documents, rb_documenti_cdc WHERE rb_documents.id = id_documento AND anno_scolastico = ? AND classe = ? ORDER BY tipo_documento, data_upload DESC");
$rel_stmt = $db->prepare("SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, rb_relazioni_docente.id AS rel_id, classe, owner FROM rb_documents, rb_relazioni_docente WHERE rb_documents.id = id_documento AND anno_scolastico = ? AND classe = ? ORDER BY tipo_documento, data_upload DESC");
try {
	$res_cls = $db->executeQuery("SELECT id_classe, CONCAT_WS('', anno_corso, sezione) AS cls FROM rb_classi WHERE ordine_di_scuola = {$_SESSION['__school_order__']} ORDER BY sezione, anno_corso");
	while ($row = $res_cls->fetch_assoc()) {
		$relazioni[$row['id_classe']] = array("id_classe" => $row['id_classe'], "classe" => $row['cls'], "docs" => array());
		//documenti_cdc
		$cdc_stmt->bind_param("ii", $anno, $row['id_classe']);
		$cdc_stmt->bind_result($doc_id, $data_upload, $file, $titolo, $doc_type, $categoria, $rel_id, $classe, $owner);
		$cdc_stmt->execute();
		while ($cdc_stmt->fetch()) {
			if (!isset($relazioni[$row['id_classe']]['docs'][$doc_id])) {
				if ($categoria < 4) {
					$titolo = substr($titolo, 0, strlen($titolo) - 3);
				}
				else {
					$titolo = substr($titolo, 0, strlen($titolo) - 11);
				}
				$relazioni[$row['id_classe']]['docs'][$doc_id] = array("id_documento" => $doc_id, "tipo" => $doc_type, "data" => $data_upload, "file" => $file, "titolo" => $titolo, "id_relazione" => $rel_id, "classe" => $classe, "owner" => $owner);
			}
		}
		//relazioni
		$rel_stmt->bind_param("ii", $anno, $row['id_classe']);
		$rel_stmt->bind_result($doc_id, $data_upload, $file, $titolo, $doc_type, $rel_id, $classe, $owner);
		$rel_stmt->execute();
		while ($rel_stmt->fetch()) {
			if (!isset($relazioni[$row['id_classe']]['docs'][$doc_id])) {
				$relazioni[$row['id_classe']]['docs'][$doc_id] = array("id_documento" => $doc_id, "tipo" => $doc_type, "data" => $data_upload, "file" => $file, "titolo" => substr($titolo, 0, strlen($titolo) - 11), "id_relazione" => $rel_id, "classe" => $classe, "owner" => $owner);
			}
		}
	}
} catch (MySQLException $ex) {
	$ex->redirect();
}

$_SESSION['relazioni'] = $relazioni;

$drawer_label = "Elenco relazioni per classe";

include "relazioni.html.php";
