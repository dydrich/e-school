<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 06/07/14
 * Time: 12.32
 */

require_once "../lib/start.php";
require_once "../lib/data_source.php";
require_once "../lib/SuDo.php";

check_session();
//check_permission(ADM_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$dl = new MySQLDataLoader($db);
$sudo_manager = new \eschool\SuDo($dl);

switch ($_REQUEST['action']){
	case "sudo":
		$area = $_REQUEST['area'];
		$uid = $_REQUEST['uid'];
		$go = $sudo_manager->sudo($area, $uid);
		break;
	case "back":
		$go = $sudo_manager->back();
		break;
}

if ($go){
	header("Location: ../index.php");
}
exit;