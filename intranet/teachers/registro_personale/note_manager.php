<?php

require_once "../../../lib/start.php";
require_once "../../../lib/TeachingNote.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$id_nota = $_REQUEST['id_nota'];
if ($_REQUEST['action'] == "new" || $_REQUEST['action'] == "update") {
	$teacher = $_SESSION['__user__']->getUid();
	$class = $_SESSION['__classe__']->get_ID();
	$subject = $_SESSION['__materia__'];
	$year = $_SESSION['__current_year__']->get_ID();
	$stid = $_REQUEST['stid'];
	$type = $_REQUEST['ntype'];
	$desc = utf8_encode($db->real_escape_string($_REQUEST['desc']));
	$date = format_date($_REQUEST['ndate'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
}

switch($_REQUEST['action']){
	case "new":
		$data = array();
		$data['id'] = 0;
		$data['docente'] = $teacher;
		$data['classe'] = $class;
		$data['alunno'] = $stid;
		$data['tipo'] = $type;
		$data['materia'] = $subject;
		$data['anno'] = $year;
		$data['data'] = $date;
		$data['note'] = $desc;
		$teachingNote = new \eschool\TeachingNote(0, $data, new MySQLDataLoader($db));
		try{
			$newId = $teachingNote->save();
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$response['id'] = $newId;
		echo json_encode($response);
		exit;
		break;
	case "update":
		$id_nota = $_REQUEST['id_nota'];
		$data = array();
		$data['id'] = $id_nota;
		$data['docente'] = $teacher;
		$data['classe'] = $class;
		$data['alunno'] = $stid;
		$data['tipo'] = $type;
		$data['materia'] = $subject;
		$data['anno'] = $year;
		$data['data'] = $date;
		$data['note'] = $desc;
		$teachingNote = new \eschool\TeachingNote($id_nota, $data, new MySQLDataLoader($db));
		try{
			$newId = $teachingNote->save();
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
	case "delete":
		$id_nota = $_REQUEST['id_nota'];
		$teachingNote = new \eschool\TeachingNote($id_nota, null, new MySQLDataLoader($db));
		try {
			$id = $teachingNote->delete();
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
	case "get":
		$mysql = new MySQLDataLoader($db);
		$data = $mysql->executeQuery("SELECT * FROM rb_note_didattiche WHERE id_nota = {$id_nota}");
		$data[0]['data'] = format_date($data[0]['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
		$response['note'] = $data[0];
		echo json_encode($response);
		exit;
		break;
}
