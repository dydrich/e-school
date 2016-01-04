<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 31/12/15
 * Time: 15.37
 * create and download monthly reports
 */
require_once "../lib/start.php";
require_once "../lib/ReportManager.php";

ini_set("display_errors", DISPLAY_ERRORS);

$response = ["status" => "ok", "message" => "Operazione completata"];

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'search') {
	header("Content-type: application/json");
	$cls = $_REQUEST['cls'];
	$rep_id = $_REQUEST['id'];
	$report_manager = new ReportManager($db, $_SESSION['__current_year__']->get_ID(), 1);
	try {
		$response['students'] = $report_manager->searchMonthlyReports($rep_id, $cls);
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
		echo json_encode($response);
		exit;
	}
	if (!$response['students']) {
		$response['status'] = "no_st";
		$response['message'] = "Nessuna segnalazione presente";
		echo json_encode($response);
		exit;
	}
	echo json_encode($response);
	exit;
}

$st = $_REQUEST['st'];
$month = $_REQUEST['m'];

$report_manager = new ReportManager($db, $_SESSION['__current_year__']->get_ID(), 1);
$file = $report_manager->createMonthlyReport($st, $month);

$link = "../modules/documents/download_manager.php?doc=monthly_report&school_order=1&area=genitori&st={$st}&f=".$file;
header("Location: $link");
