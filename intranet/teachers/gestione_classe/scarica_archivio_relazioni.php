<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 19/08/15
 * Time: 17.49
 * download in formato zip di tutti i documenti di programmazione associati alla classe
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/RBUtilities.php";
require_once "../../../lib/MimeType.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM|SEG_PERM|DSGA_GROUP|DIR_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$page = getFileName();

$docs = $_SESSION['relazioni'];
$dir = $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$ordine_di_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_order_directory = "scuola_media";
if ($ordine_di_scuola == 2){
	$school_order_directory = "scuola_primaria";
}

$rb = RBUtilities::getInstance($db);

chdir('../../../tmp');

$zip = new ZipArchive();
$filename = "relazioni".$dir.".zip";
if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
	exit("cannot open <$filename>\n");
}

$sel_anni = "SELECT id_anno, descrizione FROM rb_anni ORDER BY id_anno DESC";
try{
	$res_anni = $db->executeQuery($sel_anni);
} catch(MySQLException $ex){
	$ex->redirect();
}
$anni = array();
while ($row = $res_anni->fetch_assoc()) {
	$anni[$row['id_anno']] = $row;
}
$descrizione = $_SESSION['__current_year__']->get_descrizione();
if (isset($_REQUEST['y'])) {
	$descrizione = $anni[$_REQUEST['y']]['descrizione'];
}

$docs_11 = array();
chdir("../download/registri/{$descrizione}/{$school_order_directory}/docenti/");
foreach ($docs as $doc) {
	if ($doc['tipo'] == 11) {
		// documenti del cdc
		$docs_11[] = $doc;
	}
	else {
		$us = $rb->loadUserFromUid($doc['owner'], "school");
		$user_directory = $us->getFullName();
		$user_directory = preg_replace("/ /", "_", $user_directory);
		$user_directory = strtolower($user_directory);
		chdir($user_directory);
		$zip->addFile($doc['file']);
		chdir("../");
	}
}

chdir("../../../../11/");
foreach ($docs_11 as $doc) {
	$zip->addFile($doc['file']);
}

$zip->close();

chdir("../../tmp/");

$mime = MimeType::getMimeContentType($filename);

//$fp = "../../".$this->getFilePath().$this->file;
header("Content-Type: ".$mime['ctype']);
header("Content-Disposition: attachment; filename=".$filename);
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");
readfile($filename);

unlink($filename);