<?php

require_once "../lib/SchoolYearManager.php";
require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", "1");

$symanager = new SchoolYearManager($db);
if(isset($_POST['school_order'])){
	$symanager->setYear($_SESSION['__school_year__'][$_POST['school_order']]);
}
else if(!$_SESSION['__user__']->isAdministrator()){
	$symanager->setYear($_SESSION['__school_year__'][$_SESSION['__school_order__']]);
}
else{
	$symanager->setYear(new SchoolYear($_SESSION['__current_year__']));
}

switch($_REQUEST['action']){
	case "new":
		$start = $_POST['data_inizio'];
		$end = $_POST['data_fine'];
		try{
			$symanager->startTransaction();
			$id = $symanager->createNewYear($start, $end);
			$symanager->doCommit();
		} catch (MySQLException $ex){
			echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
			$symanager->doRollback();
			exit;
		}
		break;
	case "basic_update":
		$start = format_date($_POST['data_inizio'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$end = format_date($_POST['data_fine'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		try{
			$symanager->startTransaction();
			$q = $symanager->updateBasicData($start, $end);
			$symanager->doCommit();
			$_SESSION['__current_year__'] = $symanager->getYear()->getYear();
		} catch (MySQLException $ex){
			echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
			$symanager->doRollback();
			exit;
		}
		break;
	case "save_data":
		$start_lessons = format_date($_POST['data_inizio_lezioni'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$end_lessons = format_date($_POST['data_fine_lezioni'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$sessions = $_POST['sessions'];
		$session1 = format_date($_POST['session1'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$row = array();
		$row['sessions'] = $sessions;
		$row['classes_start'] = $start_lessons;
		$row['classes_end'] = $end_lessons;
		$row['session1'] = $session1;
		if($sessions == 3){
			$session2 = format_date($_POST['session2'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
			$row['session2'] = $session2;
		}
		else{
			$row['session2'] = "";
		}
		$row['holydays'] = $_POST['vacanze'];
		try{
			$symanager->startTransaction();
			$symanager->saveLessonsData($row);
			$symanager->doCommit();
			$_SESSION['__school_year__'][$_SESSION['__school_order__']] = $symanager->getYear();
		} catch (MySQLException $ex){
			echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
			$symanager->doRollback();
			exit;
		}
	default:
		
		break;
}

echo "ok";
exit;
