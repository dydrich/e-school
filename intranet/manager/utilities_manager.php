<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 06/07/14
 * Time: 22.08
 */

require_once "../../lib/start.php";
require_once "../../lib/RBUtilities.php";

check_session();
check_permission(DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$response = array("status" => "ok", "message" => "ok");

$year_desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID());

$rb = RBUtilities::getInstance($db);

if ($_REQUEST['action'] == "createreports"){
	$file = RBUtilities::createAllReportsArchive($year_desc);
}
else {
	$file = RBUtilities::createAllTeachersBooksArchive($year_desc);
}

header("Content-type: application/json");
$response['file'] = $file;
echo json_encode($response);
exit;