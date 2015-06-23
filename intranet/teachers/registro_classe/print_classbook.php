<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Classbook.php";
require_once "../../../lib/RBUtilities.php";

check_session();
check_permission(SEG_PERM|DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if ($_SESSION['__area__'] == "manager"){
	$school_year = $_SESSION['__school_year__'][$_SESSION['__school_order__']];
}
else{
	$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
	$school_year = $_SESSION['__school_year__'][$ordine_scuola];
}

if ($_REQUEST['action'] == "zip") {
	$ordine_scuola = $_SESSION['__school_order__'];
	$dir = "";
	if ($ordine_scuola == 1) {
		$dir = "scuola_media";
		$lev = "scuola-media";
	}
	else {
		$dir = "scuola_primaria";
		$lev = "scuola-primaria";
	}
	$year_desc = $_SESSION['__current_year__']->get_descrizione();
	$old_dir = getcwd();
	chdir($_SESSION['__path_to_root__']."download/registri/".$_SESSION['__current_year__']->get_descrizione()."/".$dir."/");
	$zip = new ZipArchive();
	$file_zip = "registri-classe"."_".$lev."_".$year_desc.".zip";
	if (file_exists($file_zip)){
		unlink($file_zip);
	}
	if ($zip->open($file_zip, ZipArchive::CREATE)!==TRUE) {
		exit("cannot open <$file_zip>\n");
	}
	$root_path = "./classi/";
	$files = new RecursiveIteratorIterator (new RecursiveDirectoryIterator($root_path), RecursiveIteratorIterator::LEAVES_ONLY);
	foreach ($files as $name => $file) {
		$filePath = $file->getRealPath();
		$file_dirs = explode("/", $filePath);
		$act_dirs = array_slice($file_dirs, (count($file_dirs) - 4));
		$path = implode("/", $act_dirs);
		//echo $path."\n";
		if ($file->getBasename() != '.' && $file->getBasename() != '..'){
			$zip->addFile($filePath, $path);
		}
	}
	$zip->close();
	chdir($old_dir);
	header("Content-Type: application/zip");
	header("Content-Disposition: attachment; filename=".$file_zip);
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Pragma: public");
	readfile($_SESSION['__path_to_root__']."download/registri/".$_SESSION['__current_year__']->get_descrizione()."/".$dir."/".$file_zip);
}

$cls = $_REQUEST['cls'];
$rb = RBUtilities::getInstance($db);
$classe = $rb->loadClassFromClassID($cls);

$ordine_scuola = $classe->getSchoolOrder();
$dir = "";
if ($ordine_scuola == 1) {
	$dir = "scuola_media";
	$lev = "scuola-media";
}
else {
	$dir = "scuola_primaria";
	$lev = "scuola-primaria";
}

$path = $_SESSION['__path_to_root__']."download/registri/".$_SESSION['__current_year__']->get_descrizione()."/".$dir."/classi/";
@mkdir($path, 0755, true);

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

$cb = new Classbook($classe, $school_year, "", $db, $path);
try {
	$cb->createPDF();
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['query'] = $ex->getQuery();
	$response['dbg_message'] = $ex->getMessage();
	$res = json_encode($response);
	echo $res;
	exit;
}

$datetime = $cb->getCreationDateTime();
$date = substr($datetime, 0, 10);
$time = substr($datetime, 11, 5);
$string_date = " (ultima modifica il ".format_date($date, SQL_DATE_STYLE, IT_DATE_STYLE, "/")." alle ".$time.")";
$response['datetime'] = $string_date;
$res = json_encode($response);
echo $res;
exit;
