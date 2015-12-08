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
$response = array("status" => "ok", "message" => "Operazione completata");

$start = format_date($_POST['start'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$end = format_date($_POST['end'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$month = $_POST['month'];
$year = $_SESSION['__current_year__']->get_ID();

$months = array("11" => "Novembre", "12" => "Dicembre", "1" => "Gennaio", "3" => "Marzo", "4" => "Aprile", "5" => "Maggio");

$sel_pag = "SELECT COUNT(*) FROM rb_pagellini WHERE anno_scolastico = {$year} AND mese = {$month}";
$present = $db->executeCount($sel_pag);

if ($present > 0) {
	$response['message'] = 'Pagellino presente in archivio';
	$response['status'] = 'ko';
	echo json_encode($response);
	exit;
}

try {
	$response['id'] = $db->executeUpdate("INSERT INTO rb_pagellini (mese, data_apertura, data_chiusura, anno_scolastico) VALUES ({$month}, '{$start}', '{$end}', {$year})");
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
	echo json_encode($response);
	exit;
}

$response['mese'] = $months[$month];
echo json_encode($response);
exit;
