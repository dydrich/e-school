<?php

require_once "../lib/start.php";
require_once "../lib/ScheduleManager.php";
require_once "../lib/RBUtilities.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$schedule_manager = new ScheduleManager($db, $_SESSION['__current_year__']->get_ID());
$return = "ok";

$rb = RBUtilities::getInstance($db);
if($_POST['cls']){
	$_class = $rb->loadClassFromClassID($_POST['cls']);
}

if ($_REQUEST['action']) {
	switch ($_REQUEST['action']) {
		case "delete":
			try{
				$schedule_manager->deleteSchedule();
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}
			break;
		case "insert":			
			try{
				$schedule_manager->insertSchedule();
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}
			break;
		case "reinsert":
			try{
				$schedule_manager->reinsertSchedule();
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}
			break;
		case "class_delete":
			try{
				$schedule_manager->deleteClassSchedule($_class);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}			
			break;
		case "class_insert":
			try{
				$schedule_manager->insertClassSchedule($_class);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}
			break;
		case "class_reinsert":
			try{
				$schedule_manager->reinsertClassSchedule($_class);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				$response['status'] = "kosql";
				$response['message'] = "Operazione non completata a causa di un errore";
				$response['dbg_message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}
			break;
	}
}

echo json_encode($response);
exit;
