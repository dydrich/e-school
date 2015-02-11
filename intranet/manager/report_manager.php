<?php

require_once "../../lib/start.php";
require_once "../../lib/ReportManager.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM|DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$response = array("status" => "ok", "message" => "");

$report_manager = new ReportManager($db, $_REQUEST['y'], $_SESSION['__school_order__']);
$year = $_REQUEST['y'];
if (isset($_POST['cls']) && $_POST['cls'] == ""){
	$_POST['cls'] = 0;
}
$this_year = $_SESSION['__current_year__']->get_ID();

$field = "disponibili_docenti";
if ($_SESSION['__school_order__'] == 2){
	$field = "disponibili_docenti_sp";
}

if ($year == $this_year){
	/*
	 * pagelle finali
	 */
	//$sel_dt2q = "SELECT data_pubblicazione, ora_pubblicazione, disponibili_docenti FROM rb_pubblicazione_pagelle WHERE anno = {$year} AND quadrimestre = 2";
	$sel_dt2q = "SELECT {$field} FROM rb_pubblicazione_pagelle WHERE anno = {$year} AND quadrimestre = 2";
	$res_dt2q = $db->executeQuery($sel_dt2q);
	$dt2q = $res_dt2q->fetch_assoc();
	$dt2 = $dt2q[$field];
    //echo $dt2;

	/*
	 * pagelle 1q
	*/
	$sel_dt1q = "SELECT {$field} FROM rb_pubblicazione_pagelle WHERE anno = {$year} AND quadrimestre = 1";
	$res_dt1q = $db->executeQuery($sel_dt1q);
	$dt1q = $res_dt1q->fetch_assoc();
	$dt1 = $dt1q[$field];

	if (isset($_REQUEST['q']) && $_REQUEST['q'] != 0){
		$q = $_REQUEST['q'];
	}
	else {
		$q = 1;
		$today = date("Y-m-d");

		if ($today >= $dt2){
			$q = 2;
		}
		else if ($today < $dt1){
			$q = 0;
		}
	}
}
else {
	$q = 2;
}
//echo "Q=$q";
if ($q == 0){
	$response['status'] = "nopg";
	$response['message'] = "Pagelle non disponibili";
	echo json_encode($response);
	exit;
}

switch ($_REQUEST['action']){
	case "search":
		// create on-fly pdf report and download it
		header("Content-type: application/json");
		$params = array("lname" => $_POST['lname'], "cls" => $_POST['cls'], "session" => $q);
		if ($q == 1){
			try {
				$response = $report_manager->searchReport($q, $params);
			} catch (MySQLException $ex){
				$response['status'] = "kosql";
				$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
				echo json_encode($response);
				exit;
			}
			if (!$response){
				$response['status'] = "nostd";
				$response['message'] = "Nessuno studente trovato";
				echo json_encode($response);
				exit;
			}
		}
		else {
			/*
			 * download pagella finale
			*/
			header("Content-type: application/json");
			try {
				$response = $report_manager->searchReport($q, $params);
			
			} catch (MySQLException $ex){
				$response['status'] = "kosql";
				$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
				echo json_encode($response);
				exit;
			}
				
		}
		$json = array();
		foreach ($response as $row){
			$json[$row['alunno']] = array("alunno" => $row['alunno'], "nome" => $row['cognome']." ".$row['nome'], "file" => $row['file'], "del" => 1);
		}
		echo json_encode($json);
		exit;		
		break;
	case "create_final_report":
		header("Content-type: application/json");
		try {
			$report_manager->createFinalReports();
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
			echo json_encode($response);
			exit;
		}
		echo json_encode($response);
		exit;
		break;
	case "do_backup":
		header("Content-type: application/json");
		$session = $_REQUEST['q'];
		try {
			$response['zip'] = $report_manager->doBackup($session);
		} catch (MySQLException $ex){
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$response['message'] = "Operazione conclusa. Tra qualche secondo le pagelle saranno disponibili";
		echo json_encode($response);
		exit;
		break;
}
