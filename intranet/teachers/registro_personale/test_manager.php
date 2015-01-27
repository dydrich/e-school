<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Test.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$prove = array();
$sel_prove = "SELECT * FROM rb_tipologia_prove ";
try {
	$res_prove = $db->executeQuery($sel_prove);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}
while ($row = $res_prove->fetch_assoc()) {
	$prove[$row['id']] = $row['tipologia'];
}

$data = null;
if($_REQUEST['do'] == "insert" || $_REQUEST['do'] == "update" ){
	$data = array();

	$date_from = $_REQUEST['date_time'];
	if (strlen($date_from) > 10) {
		$date_from = substr($date_from, 0, 10);
	}
	//$date_from = format_date($date_from, IT_DATE_STYLE, SQL_DATE_STYLE, "-")." 00:00:00";
	$date_from = format_date($date_from, IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$teacher = $_SESSION['__user__']->getUid();
	$year = $_SESSION['__current_year__']->get_ID();
	$subj = $_SESSION['__materia__'];
	$class = $_SESSION['__classe__']->get_ID();
	$prova = $db->real_escape_string($_REQUEST['test']);
	$subject = $db->real_escape_string($_REQUEST['subject']);
	$notes = $db->real_escape_string($_REQUEST['notes']);
	$tipo = $_REQUEST['tipo'];
	$act_id = 0;

	$data['id_docente'] = $teacher;
	$data['id_classe'] = $class;
	$data['id_anno'] = $year;
	$data['data_verifica'] = $date_from;
	$data['data_assegnazione'] = null;
	$data['id_materia'] = $subj;
	$data['valutata'] = 0;
	$data['tipologia'] = $tipo;
	$data['prova'] = $prova;
	$data['argomento'] = utf8_encode($subject);
	$data['note'] = $notes;
	$data['id_attivita'] = 0;
}

switch($_REQUEST['do']){
	case "insert":
		try{
			$test = new \eschool\Test(0, new MySQLDataLoader($db), $data, false);
			$response['id'] = $test->save();
			$response['date'] = $test->testDateToString();
			setlocale(LC_TIME, "it_IT.utf8");
			$response['date_string'] = $giorno_str = strftime("%A %d %B", strtotime($date_from));
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case "update":
		try{
			$test = new \eschool\Test($_REQUEST['id_verifica'], new MySQLDataLoader($db), $data, false);
			$test->save();
			$response['date'] = $test->testDateToString();
			$response['tp'] = $prove[$tipo];
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case "delete_test":
		try{
			$test = new \eschool\Test($_REQUEST['id_verifica'], new MySQLDataLoader($db), null, false);
			$test->delete(false);
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case "delete_all":
		try{
			$test = new \eschool\Test($_REQUEST['id_verifica'], new MySQLDataLoader($db), null, false);
			$test->delete(true);
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case "save_los":
		$goals = $_REQUEST['goals'];
		try{
			$test = new \eschool\Test($_REQUEST['id_verifica'], new MySQLDataLoader($db), null, false);
			$test->setLearningObjectives($goals);
			$test->saveLearningObjectives();
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case "update_grade":
		$id_verifica = $_REQUEST['verifica'];
		$id_voto = $_REQUEST['id_voto'];
		$voto = $_REQUEST['voto'];
		$alunno = $_REQUEST['alunno'];
		try{
			$test = new \eschool\Test($id_verifica, new MySQLDataLoader($db), null, true);
			$response['idv'] = $test->setGrade($voto, $id_voto, $alunno);
			//echo $test->getEvaluatedStudents();
			$response['count'] = $test->getEvaluatedStudents();
			$response['media'] = $test->getAverage();
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			$response['status'] = "kosql";
			$response['message'] = "Errore nella registrazione dei dati";
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}


		break;
}

echo json_encode($response);
exit;
