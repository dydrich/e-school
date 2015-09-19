<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/09/15
 * Time: 14.30
 */

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";
$page = "programmazioni.php";
$area = "genitori";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$drawer_label = "Programmazioni della classe";

$sel_rel = "SELECT rb_documents.id AS doc_id, data_upload, file, titolo, doc_type, rb_relazioni_docente.id AS rel_id, classe, owner, categoria FROM rb_documents, rb_relazioni_docente WHERE rb_documents.id = id_documento AND anno_scolastico = {$_SESSION['__current_year__']->get_ID()} AND classe = {$_SESSION['__classe__']->get_ID()} AND tipo_documento = 6 ORDER BY data_upload DESC";
$relazioni = array();
try {
	$res_rel = $db->executeQuery($sel_rel);
} catch (MySQLException $ex) {
	$ex->redirect();
}
while ($row = $res_rel->fetch_assoc()) {
	if (!isset($relazioni[$row['doc_id']])) {
		$relazioni[$row['doc_id']] = array("id_documento" => $row['doc_id'], "tipo" => $row['doc_type'], "data" => $row['data_upload'], "file" => $row['file'], "titolo" => substr($row['titolo'], 0, strlen($row['titolo']) - 12), "id_relazione" => $row['rel_id'], "classe" => $row['classe'], "owner" => $row['owner']);
	}
}

$_SESSION['relazioni'] = $relazioni;

include "programmazioni.html.php";
