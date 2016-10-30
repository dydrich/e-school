<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/30/16
 * Time: 10:49 AM
 * gestione colloqui periodici
 */
require_once "../../lib/start.php";
require_once "../../lib/ParentsMeetings.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$response = array("status" => "ok", "message" => "Operazione completata");

$meetings_manager = new \eschool\ParentsMeetings($_SESSION['__school_order__'], new MySQLDataLoader($db));

switch ($_POST['action']) {
	case 'insert_school_meeting':
		$date = $_POST['date'];
		$meetings_manager->addMeeting($date);
		break;
	case 'delete_school_meeting':
		$id = $_POST['id'];
		$meetings_manager->deleteMeeting($id);
		break;
}

$res = json_encode($response);
echo $res;
exit;