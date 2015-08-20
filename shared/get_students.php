<?php

require_once "../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$sel_students = "SELECT * FROM rb_alunni WHERE attivo = '1' AND id_classe = ".$_REQUEST['classe']." ORDER BY cognome, nome";
try {
	$result = $db->executeQuery($sel_students);
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$students = array();
while ($st = $result->fetch_assoc()) {
	$students[] = array("id" => $st['id_alunno'], "name" => $st['cognome']." ".$st['nome']);
}

$response['data'] = $students;
echo json_encode($response);
exit;
