<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: application/json");
$response = array("status" => "ok");

$sel_classi = "SELECT id_classe, tempo_prolungato FROM rb_classi ORDER BY id_classe";
try{
	$res_classi = $db->executeQuery($sel_classi);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

$action = null;
if (!$_REQUEST['action']) {
	$action = "insert";
}
else {
	$action = $_REQUEST['action'];
}

$anno = $_SESSION['__current_year__']->get_ID();

$giorni = array(1, 2, 3, 4, 5, 6);
$max_ore = 8;

if ($action == "reinsert") {
	try {
		$db->executeUpdate("DELETE FROM rb_orario WHERE anno = {$anno}");
	} catch (MySQLException $ex){
        $response['status'] = "kosql";
		$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
		$response['message'] = "Errore nella registrazione dei dati";
		$res = json_encode($response);
		echo $res;
		exit;
    }
}
while($classe = $res_classi->fetch_assoc()){
	if($classe['tempo_prolungato'] != 1)
		$max = 5;
	else
		$max = $max_ore;
	foreach($giorni as $giorno){
		for($i = 0; $i < $max; $i++){
			$ins = "INSERT INTO rb_orario (giorno, ora, classe, anno) VALUES ($giorno, ".($i + 1).", ".$classe['id_classe'].", $anno)";
			try{
				$r_ins = $db->executeUpdate($ins);
			} catch (MySQLException $ex){
	        	$response['status'] = "kosql";
				$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
				$response['message'] = "Errore nella registrazione dei dati";
				$res = json_encode($response);
				echo $res;
				exit;
	        }
		}
	}
}

$response['status'] = "ok";
$response['message'] = $msg;
$res = json_encode($response);
echo $res;
exit;