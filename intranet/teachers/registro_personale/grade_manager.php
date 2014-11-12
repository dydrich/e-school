<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 21/07/14
 * Time: 16.30
 */

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";
require_once "../../../lib/data_source.php";
require_once "../../../lib/Grade.php";
require_once "../../../lib/StudentStats.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$docente = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$materia = $_SESSION['__materia__'];
if(isset($_REQUEST['q'])) {
	$q = $_REQUEST['q'];
}
else {
	$q = 0;
}
switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		break;
}

$action = $_REQUEST['action'];
if ($action == 'new' || $action == "update") {
	$data = array();
	$data['voto'] = $_REQUEST['voto'];
	$data['materia'] = $materia;
	$data['docente'] = $docente;
	$data['alunno'] = $_REQUEST['id_alunno'];
	$data['data_voto'] = format_date($_REQUEST['data_voto'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$data['anno'] = $anno;
	$data['descrizione'] = $db->real_escape_string($_REQUEST['descrizione']);
	$data['tipologia'] = $_REQUEST['tipologia'];
	$data['argomento'] = $db->real_escape_string($_REQUEST['argomento']);
	if (isset($_REQUEST['note'])) {
		$data['note'] = $db->real_escape_string($_REQUEST['note']);
	}
	else {
		$data['note'] = "";
	}
	if (isset($_REQUEST['privato'])) {
		$data['privato'] = $_REQUEST['privato'];
	}
	else {
		$data['privato'] = 0;
	}

	$data['id_verifica'] = $_REQUEST['verifica'];
}

switch($action){
	case "new":
		$data['id_voto'] = 0;
		$grade = new Grade(0, $data, new MySQLDataLoader($db));
		try {
			$id = $grade->save();
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$stats = new \eschool\StudentStats(new MySQLDataLoader($db), $data['alunno'], $school_year);
		$response['id'] = $id;
		$response['voto'] = $grade->getGrade(true);
		$response['data'] = $stats->getGradesAvg($materia, $_REQUEST['tipologia'], $q);
		$response['all'] = $stats->getGradesAvg($materia, null, $q);
		/*
		 * recupero posizione voto
		 */
		$sel_ids = "SELECT id_voto FROM rb_voti WHERE anno = $anno AND docente = $docente AND materia = $materia AND alunno = ".$_REQUEST['id_alunno'];
		if (isset($_REQUEST['q'])) {
			$sel_ids .= " ".$int_time;
		}
		$sel_ids .= "  ORDER BY data_voto DESC";
		$res_ids = $db->executeQuery($sel_ids);
		$previous = "";
		while ($row = $res_ids->fetch_assoc()) {
			if ($row['id_voto'] == $id) {
				break;
			}
			$previous = $row['id_voto'];
		}
		$response['previous'] = $previous;
		echo json_encode($response);
		exit;
		break;
	case "update":
		$id_voto = $_REQUEST['id_voto'];
		$data['id_voto'] = $id_voto;
		$grade = new Grade($id_voto, $data, new MySQLDataLoader($db));
		try {
			$id = $grade->save();
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$stats = new \eschool\StudentStats(new MySQLDataLoader($db),$_REQUEST['id_alunno'], $school_year);
		$response['id'] = $id;
		$response['voto'] = $grade->getGrade(true);
		$response['data'] = $stats->getGradesAvg($materia, $_REQUEST['tipologia'], $q);
		$response['all'] = $stats->getGradesAvg($materia, null, $q);
		echo json_encode($response);
		exit;
		break;
	case "delete":
		$id_voto = $_REQUEST['id_voto'];
		$grade = new Grade($id_voto, null, new MySQLDataLoader($db));
		try {
			$id = $grade->delete();
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$stats = new \eschool\StudentStats(new MySQLDataLoader($db),$_REQUEST['id_alunno'], $school_year);
		$response['id'] = $id;
		$response['voto'] = $grade->getGrade(true);
		$response['data'] = $stats->getGradesAvg($materia, $_REQUEST['tipologia'], $q);
		$response['all'] = $stats->getGradesAvg($materia, null, $q);
		echo json_encode($response);
		exit;
		break;
	case "get":
		$id_voto = $_REQUEST['id_voto'];
		$mysql = new MySQLDataLoader($db);
		try {
			$data = $mysql->executeQuery("SELECT * FROM rb_voti WHERE id_voto = {$id_voto}");
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$data[0]['data_voto'] = format_date($data[0]['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
		$response['grade'] = $data[0];
		echo json_encode($response);
		exit;
		break;
	case "update_los":
		$goal = $_REQUEST['goal'];
		$voto = $_REQUEST['grade'];
		$gradeID = $_REQUEST['gradeID'];
		try {
			$grade = new Grade($gradeID, null, new MySQLDataLoader($db));
			$grade->updateLearningObjectiveGrade($voto, $goal);
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		echo json_encode($response);
		exit;
		break;
	default:

		break;
}
