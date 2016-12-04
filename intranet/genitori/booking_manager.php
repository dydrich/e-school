<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/31/16
 * Time: 10:58 AM
 * prenotazione colloqui periodici
 */
require_once "../../lib/start.php";
require_once "../../lib/ParentsMeetingsManager.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$response = array("status" => "ok", "message" => "Operazione completata");

$meetings_manager = new \eschool\ParentsMeetingsManager($_SESSION['__school_order__'], new MySQLDataLoader($db));

switch ($_POST['action']) {
	case 'book':
		$date = $_POST['date'];
		$teacher = $_POST['teacher'];
		$meetings_manager->bookAMeeting($date, $teacher, $_SESSION['__user__']->getUid());
		break;
	case 'delete_booking':
		$date = $_POST['date'];
		$teacher = $_POST['teacher'];
		$meetings_manager->deleteBooking($date, $teacher, $_SESSION['__user__']->getUid());
		break;
}

$res = json_encode($response);
echo $res;
exit;