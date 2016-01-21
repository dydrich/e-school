<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 20/01/16
 * Time: 17.44
 */
require_once "../../../lib/start.php";
require_once "../../../lib/ClassroomReservationBook.php";
require_once "../../../lib/Classroom.php";
require_once "../../../lib/RBUtilities.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$response = ["status" => "ok", "message" => "I dati sono stati aggiornati"];
header("Content-type: application/json");

$id = $_REQUEST['lab'];
$day = format_date($_REQUEST['day'], IT_DATE_STYLE, SQL_DATE_STYLE, '-');
$action = $_REQUEST['action'];

try {
	$classroom = new \eschool\Classroom($id, new MySQLDataLoader($db), null, null, true);

	if ($action == 'check') {
		$data = $classroom->getDay($day);
		if ($data) {
			$response['data'] = [];
			foreach ($data as $k => $item) {
				$teacher = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) AS name FROM rb_utenti WHERE uid = {$item['teacher']}");
				$cls = $db->executeCount("SELECT CONCAT(anno_corso, sezione) FROM rb_classi WHERE id_classe = {$item['class']}");
				$item['desc_cls'] = $cls;
				$item['desc_tea'] = $teacher;
				if ($item['teacher'] == $_SESSION['__user__']->getUid()) {
					$item['delete'] = true;
				}
				else {
					$item['delete'] = false;
				}
				$response['data'][$k] = $item;
			}
		}
	}
	else if ($action == 'book') {
		$cls = $_SESSION['__classe__']->get_ID();
		$teacher = $_SESSION['__user__']->getUid();
		$hour = $_REQUEST['hour'];
		$classroom->reserve($day, $hour, $cls, $teacher);
	}
	else if ($action == 'del') {
		$hour = $_REQUEST['hour'];
		$classroom->deleteReservation($day, $hour);
	}
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;
