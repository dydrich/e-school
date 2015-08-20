<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 19/08/15
 * Time: 17.49
 * download in formato zip di tutti i documenti di programmazione associati alla classe
 * area segreteria
 */
require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";
require_once "../../lib/RBUtilities.php";
require_once "../../lib/MimeType.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$page = getFileName();

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);
$anno = $_SESSION['__current_year__']->get_ID();
$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];
$school_order_directory = "scuola_media";
if ($_SESSION['__school_order__'] == 2){
	$school_order_directory = "scuola_primaria";
}
$year_dir = $_SESSION['__current_year__']->get_descrizione();

$rb = RBUtilities::getInstance($db);

$dir = "";
if (isset($_REQUEST['classe'])) {
	$cls = $rb->loadClassFromClassID($_REQUEST['classe']);
	$dir = $cls->get_anno().$cls->get_sezione();
}

$root = $_SESSION['__config__']['html_root'];
$path_to_user_docs = "/download/registri/{$year_dir}/{$school_order_directory}/docenti/";
$path_to_cdc_docs  = "/download/11/";

$docs_11 = array();

chdir($root.'/tmp/');

$zip = new ZipArchive();
$filename = "relazioni".$dir.".zip";
if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
	exit("cannot open <$filename>\n");
}
$zip->addEmptyDir("relazioni".$year_dir);
$zip->addEmptyDir("relazioni".$year_dir."/".$school_order_directory);

$relazioni = $_SESSION['relazioni'];

foreach ($relazioni as $idc => $relazione) {
	if (isset($_REQUEST['classe']) && $_REQUEST['classe'] != $idc) {
		continue;
	}
	unset($docs_11);
	$docs_11 = array();
	$zip->addEmptyDir("relazioni".$year_dir."/".$school_order_directory."/".$relazione['classe']);
	foreach ($relazione['docs'] as $id => $doc) {
		if ($doc['tipo'] == 11) {
			// documenti del cdc
			$docs_11[] = $doc;
		}
		else {
			$us = $rb->loadUserFromUid($doc['owner'], "school");
			$user_directory = $us->getFullName();
			$user_directory = preg_replace("/ /", "_", $user_directory);
			$user_directory = strtolower($user_directory);
			chdir($root.$path_to_user_docs.$user_directory);
			$zip->addFile($doc['file'], "relazioni".$year_dir."/".$school_order_directory."/".$relazione['classe']."/".$doc['file']);
		}
	}
	chdir($root.$path_to_cdc_docs);
	foreach ($docs_11 as $doc) {
		$zip->addFile($doc['file'], "relazioni".$year_dir."/".$school_order_directory."/".$relazione['classe']."/".$doc['file']);
	}
}

$zip->close();

chdir($root."/tmp/");

$mime = MimeType::getMimeContentType($filename);

//$fp = "../../".$this->getFilePath().$this->file;
header("Content-Type: ".$mime['ctype']);
header("Content-Disposition: attachment; filename=".$filename);
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");
readfile($filename);

unlink($filename);
