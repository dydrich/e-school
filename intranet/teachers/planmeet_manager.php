<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/09/14
 * Time: 20.09
 */
require_once "../../lib/start.php";
require_once "../../lib/PlanningMeeting.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "Registro elettronico - Programmazione";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];

$data = array();
$data['data'] = format_date($_REQUEST['date'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$data['id_modulo'] = $_SESSION['fs_module'];
$data['ora_inizio'] = $_REQUEST['start'];
$data['ora_termine'] = $_REQUEST['end'];
$data['assenti'] = $db->real_escape_string($_REQUEST['assenti']);
$data['altro'] = $db->real_escape_string($_REQUEST['altro']);
$data['italiano'] = $db->real_escape_string($_REQUEST['italiano']);
$data['matematica'] = $db->real_escape_string($_REQUEST['matematica']);
$data['religione'] = $db->real_escape_string($_REQUEST['religione']);
$data['immagine'] = $db->real_escape_string($_REQUEST['immagine']);
$data['inglese'] = $db->real_escape_string($_REQUEST['inglese']);
$data['storia'] = $db->real_escape_string($_REQUEST['storia']);
$data['geografia'] = $db->real_escape_string($_REQUEST['geografia']);
$data['motoria'] = $db->real_escape_string($_REQUEST['motoria']);
$data['scienze'] = $db->real_escape_string($_REQUEST['scienze']);
$data['musica'] = $db->real_escape_string($_REQUEST['musica']);
$data['tecnologia'] = $db->real_escape_string($_REQUEST['tecnologia']);
$rid = $_REQUEST['rid'];

$meeting = new \eschool\PlanningMeeting($rid, $data, new MySQLDataLoader($db));

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Configurazione salvata");
try {
	switch ($_REQUEST['action']) {
		case "insert":
			$response['rid'] = $meeting->insert();
			break;
		case "update":
			$meeting->update();
			break;
		case "delete":
			$meeting->delete();
			break;
	}
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
	$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
	$res = json_encode($response);
	echo $res;
	exit;
}

$res = json_encode($response);
echo $res;
exit;
