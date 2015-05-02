<?php

require_once "../../../lib/start.php";
require_once "../../../lib/DisciplinaryNote.php";

check_session();
check_permission(DOC_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$teacher = $_SESSION['__user__']->getUid();
$class = $_SESSION['__classe__']->get_ID();
$stid = isset($_REQUEST['stid']) ? $_REQUEST['stid'] : null;
if ($_REQUEST['action'] != "delete") {
	$type = $_REQUEST['type'];
	$desc = $db->real_escape_string($_REQUEST['desc']);
	$date = format_date($_REQUEST['_date'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
}

switch($_REQUEST['action']){
	case "insert":
		$data = array();
		$data['id'] = 0;
		$data['docente'] = $teacher;
		$data['classe'] = $class;
		$data['alunno'] = $stid;
		$data['tipo'] = $type;
		$data['anno'] = $_SESSION['__current_year__']->get_ID();
		$data['descrizione'] = utf8_encode($desc);
		$data['data'] = $date;
		$data['sanzione'] = "";
		try{
			$disciplinaryNote = new \eschool\DisciplinaryNote(0, $data, new MySQLDataLoader($db));
			$id = $disciplinaryNote->save();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		//echo "===".$stid."===";
		if ($stid == null || $stid == "" || $stid == "NULL") {
			$response['class_note'] = 1;
		}
		else {
			$response['class_note'] = 0;
		}
		/*
		 * recupero posizione nota
		 */
		$sel_ids = "SELECT id_nota FROM rb_note_disciplinari WHERE anno = ".$data['anno']." AND docente = $teacher AND alunno ".field_null($stid, false, 'query');
		$sel_ids .= "  ORDER BY data DESC";
		//echo $sel_ids;
		$res_ids = $db->executeQuery($sel_ids);
		$previous = "";
		while ($row = $res_ids->fetch_assoc()) {
			if ($row['id_nota'] == $id) {
				break;
			}
			$previous = $row['id_nota'];
		}
		$response['previous'] = $previous;
		break;
	case "update":
		$id_nota = $_REQUEST['id_nota'];
		$data = array();
		$data['id'] = $id_nota;
		$data['tipo'] = $type;
		$data['descrizione'] = $desc;
		$data['data'] = $date;
		$data['docente'] = $teacher;
		$data['classe'] = $class;
		$data['alunno'] = $stid;
		$data['anno'] = $_SESSION['__current_year__']->get_ID();
		$data['sanzione'] = "";
		try{
			$disciplinaryNote = new \eschool\DisciplinaryNote(0, $data, new MySQLDataLoader($db));
			$disciplinaryNote->save();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$id = $id_nota;
		break;
	case "delete":
		$id_nota = $_REQUEST['id_nota'];
		try{
			$notes_count = $db->executeCount("SELECT COUNT(*) FROM rb_note_disciplinari WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND alunno = (SELECT alunno FROM rb_note_disciplinari WHERE id_nota = ".$id_nota.")");
			$disciplinaryNote = new \eschool\DisciplinaryNote($id_nota, null, new MySQLDataLoader($db));
			$disciplinaryNote->delete();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$id = $id_nota;
		$response['count'] = $notes_count - 1;
		break;
}

$response['id'] = $id;
echo json_encode($response);
exit;
