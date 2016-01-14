<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/11/15
 * Time: 10.45
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

header("Content-type: application/json");
$response = ["status" => "ok", "message" => "Operazione completata"];

$start = format_date($_POST['start'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$end = format_date($_POST['end'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$month = $_POST['month'];
$year = $_SESSION['__current_year__']->get_ID();
$rep_id = $_REQUEST['id'];

$months = ["11" => "Novembre", "12" => "Dicembre", "1" => "Gennaio", "3" => "Marzo", "4" => "Aprile", "5" => "Maggio"];

if ($rep_id == 0) {
	// insert
	$sql = "INSERT INTO rb_pagellini (mese, data_apertura, data_chiusura, anno_scolastico) VALUES ({$month}, '{$start}', '{$end}', {$year})";
}
else {
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
		$sql = "DELETE FROM rb_pagellini WHERE id_pagellino = {$rep_id}";
	}
	else {
		$sql = "UPDATE rb_pagellini SET data_apertura = '{$start}', data_chiusura = '{$end}', mese = {$month} WHERE id_pagellino = {$rep_id}";
		$today = date("Y-m-d");
		if ($end < $today) {
			$response['state'] = "chiuso il ".format_date($end, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
			$response['class'] = "normal";
		}
		else {
			if ($start <= $today) {
				$response['state'] = "aperto sino al ".format_date($end, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
			}
			else {
				$response['state'] = "disponibile dal ".format_date($start, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
			}
			$response['class'] = "accent_color";
		}
	}
}

try {
	if ($rep_id == 0) {
		$response['id'] = $db->executeUpdate($sql);
	}
	else {
		$db->executeUpdate($sql);
		$response['id'] = $rep_id;
	}
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
	echo json_encode($response);
	exit;
}

if (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'delete') {
	$response['mese'] = $months[$month];
}
echo json_encode($response);
exit;
