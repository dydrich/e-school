<?php

require_once "../lib/start.php";
require_once "../lib/ClassbookManager.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

header("Content-type: text/plain");

$school_order = $_POST['school_order'];
$school_year = $_SESSION['__school_year__'][$school_order];
$school_year->setSchoolOrder($school_order);
$clmanager = new ClassbookManager($db, $school_year);
$clmanager->init();
$return = "ok";

$day = format_date($_POST['day'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$_class = $_POST['cls'];
$std = $_POST['std'];

if ($_REQUEST['action']) {
	switch ($_REQUEST['action']) {
		case "delete":
			try{
				$clmanager->delete();
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "insert":			
			try{
				$clmanager->insert();
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "reinsert":
			try{
				$clmanager->reinsert();
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "class_delete":
			try{
				$clmanager->deleteClass($_class);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}			
			break;
		case "class_insert":
			try{
				$clmanager->insertClass($_class);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "class_reinsert":
			try{
				$clmanager->reinsertClass($_class);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "student_delete":
			try{
				$clmanager->deleteStudent($std);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "student_insert":
			try{
				$clmanager->insertStudent($std);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "reinsert_student":
			try{
				$clmanager->reinsertStudent($std);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "day_delete":
			try{
				$x = $clmanager->deleteDay($day);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			if (!$x){
				echo "ko;Il giorno richiesto non e` presente in archivio";
				exit;
			}
			break;
		case "day_class_delete":
				try{
					$x = $clmanager->deleteClassDay($day, $_class);
				} catch (MySQLException $ex){
					$db->execute("ROLLBACK");
					echo "kosql;".$ex->getMessage().";".$ex->getQuery();
					exit;
				}
				if (!$x){
					echo "ko;Il giorno richiesto non e` presente in archivio";
					exit;
				}
				break;
		case "day_insert":
			try{
				$x = $clmanager->insertDay($day);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			if (!$x){
				echo "ko;Il giorno richiesto e` presente in archivio";
				exit;
			}
			break;
		case "day_class_insert":
				try{
					$x = $clmanager->insertClassDay($day, $_class);
				} catch (MySQLException $ex){
					$db->execute("ROLLBACK");
					echo "kosql;".$ex->getMessage().";".$ex->getQuery();
					exit;
				}
				if (!$x){
					echo "ko;Il giorno richiesto e` presente in archivio";
					exit;
				}
				break;
		case "day_reinsert":
			try{
				$clmanager->reinsertDay($day);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			if (!$x){
				echo "ko;Il giorno richiesto non e` presente in archivio";
				exit;
			}
			break;
		case "check":
			try{
				$clmanager->checkIntegrity(false);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "correct":
			try{
				$clmanager->checkIntegrity(true);
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
		case "delete_vacation":
			try{
				$clmanager->deleteHolydays();
			} catch (MySQLException $ex){
				$db->execute("ROLLBACK");
				echo "kosql;".$ex->getMessage().";".$ex->getQuery();
				exit;
			}
			break;
	}
}

echo "ok";
exit;
