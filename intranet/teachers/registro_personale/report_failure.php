<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/11/15
 * Time: 12.10
 */
require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$year = $_SESSION['__current_year__']->get_ID();
$id_report = $_POST['id_report'];
$student = $_POST['student'];
$subject = $_POST['subject'];
$cls = $_SESSION['__classe__']->get_ID();
$action = $_POST['action'];

if ($action == 'del') {
	try {
		$db->executeUpdate("DELETE FROM rb_segnalazioni_pagellino WHERE anno = {$year} AND alunno = {$student} AND id_pagellino = {$id_report} AND materia = {$subject}");
	} catch (MySQLException $ex) {
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
		echo json_encode($response);
		exit;
	}
}
else {
	try {
		$db->executeUpdate("INSERT INTO rb_segnalazioni_pagellino (anno, id_pagellino, alunno, materia, classe) VALUES({$year}, {$id_report}, {$student}, {$subject}, {$cls})");
	} catch (MySQLException $ex) {
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage()." ===== ".$ex->getQuery();
		echo json_encode($response);
		exit;
	}
}

echo json_encode($response);
exit;
