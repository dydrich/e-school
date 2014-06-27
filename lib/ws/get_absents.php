<?php

require_once '../start.php';

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "", data => "");

/*
 * return students that were absent in given day
 */

$idreg = $_POST['id_reg'];

$sel_absent = "SELECT CONCAT_WS(' ', cognome, nome) AS nome FROM rb_alunni, rb_reg_alunni WHERE rb_alunni.id_alunno = rb_reg_alunni.id_alunno AND id_registro = {$idreg} AND ingresso IS NULL";
try {
	$res_absent = $db->executeQuery($sel_absent);
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nel recuperare i dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

if ($res_absent->num_rows > 0) {
	$data = array();
	while ($row = $res_absent->fetch_assoc()){
		$data[] = $row['nome'];
	}
	$response['data'] = implode(";", $data);
}
else {
	$response['data'] = "nessuno";
}

$res = json_encode($response);
echo $res;
exit;