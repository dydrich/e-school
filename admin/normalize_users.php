<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", "0");

if ($_REQUEST['table'] == "us"){
	$table = "rb_utenti";
	$id = "uid";
}
else if ($_REQUEST['table'] == "st"){
	$table = "rb_alunni";
	$id = "id_alunno";
}

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Normalizzazione completata");

function normalize($fname, $lname, $db){
	$names = explode(" ", $fname);
	$last_names = explode(" ", $lname);
	$ret_value = array("fname" => "", "lname" => "");
	for ($i = 0; $i < count($names); $i++){
		$names[$i] = strtoupper(substr($names[$i], 0, 1)).strtolower(substr($names[$i], 1));
	}
	for ($i = 0; $i < count($last_names); $i++){
		$last_names[$i] = strtoupper(substr($last_names[$i], 0, 1)).strtolower(substr($last_names[$i], 1));
	}
	$ret_value['fname'] = $db->real_escape_string(implode(" ", $names));
	$ret_value['lname'] = $db->real_escape_string(implode(" ", $last_names));
	
	return $ret_value;
}

$sel_names = "SELECT {$id} AS uid, nome, cognome FROM {$table}";
try{
	$res_names = $db->execute($sel_names);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

while ($row = $res_names->fetch_assoc()){
	$vals = normalize($row['nome'], $row['cognome'], $db);
	try{
		$db->executeUpdate("UPDATE {$table} SET username=LOWER(username), nome = '{$vals['fname']}', cognome = '{$vals['lname']}' WHERE {$id} = {$row['uid']}");
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
		$response['message'] = "Errore nella registrazione dei dati";
		$res = json_encode($response);
		echo $res;
		exit;
	}
}
$res = json_encode($response);
echo $res;
exit;