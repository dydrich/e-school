<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 20/06/15
 * Time: 19.38
 * gestore registri di programmazione
 */
require_once "../../lib/start.php";
require_once "../../lib/PlanningBook.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM|DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$response = array("status" => "ok", "message" => "Il registro è stato creato correttamente");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "zip") {
	$year_desc = $_SESSION['__current_year__']->get_descrizione();
	$old_dir = getcwd();
	chdir("../../download/registri/{$year_desc}/scuola_primaria/programmazione/");
	$zip = new ZipArchive();
	$file_zip = "registri-programmazione"."_".$year_desc.".zip";
	if (file_exists($file_zip)){
		unlink($file_zip);
	}
	if ($zip->open($file_zip, ZipArchive::CREATE)!==TRUE) {
		exit("cannot open <$file_zip>\n");
	}
	$root_path = "./";
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
	$response['time'] = $file_zip;
	header("Content-Type: application/zip");
	header("Content-Disposition: attachment; filename=".$file_zip);
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Pragma: public");
	readfile("../../download/registri/{$year_desc}/scuola_primaria/programmazione/$file_zip");
}

$modID = $_REQUEST['module'];

try {
	$planning_book = new PlanningBook(new MySQLDataLoader($db), $modID, null, $_SESSION['__current_year__'], null, null, null);
	$planning_book->init();
} catch (MySQLException $ex) {
	$response['status'] = 'kosql';
	$response['message'] = "Si è verificato un errore di sistema";
	$response['dbg_message'] = $ex->getMessage();
	$response['sql'] = $ex->getQuery();
	$res = json_encode($response);
	echo $res;
	exit;
}
$datetime = $planning_book->getCreationDateTime();
$date = format_date(substr($datetime, 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/");
$time = substr($datetime, 11, 5);
$response['date'] = $date;
$response['time'] = $time;
$response['href'] = $planning_book->getDirectory().$planning_book->getFile();
$res = json_encode($response);
echo $res;
exit;
