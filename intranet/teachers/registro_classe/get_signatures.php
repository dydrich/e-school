<?php

/***
 * file: get_signatures.php
 * 
 * restituisce le firme per il giorno e la classe indicati
 * 
 */

require_once "../../../lib/start.php";
require_once "../../../lib/ScheduleModule.php";
require_once "../../../lib/RBUtilities.php";

check_session();
check_permission(DOC_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

$data = format_date($_POST['data'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$classe = $_POST['classe'];

// materie
$sel_materie = "SELECT * FROM rb_materie";
try {
	$res_materie = $db->executeQuery($sel_materie);
	$materie = array();
	while($m = $res_materie->fetch_assoc()){
		$materie[$m['id_materia']] = $m['materia'];
	}
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}

$sel_dati = "SELECT id_reg, ingresso, uscita, data FROM rb_reg_classi WHERE data = '{$data}' AND id_classe = {$classe}";
try{
	$res_dati = $db->executeQuery($sel_dati);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
$dati = $res_dati->fetch_assoc();

/*
 * calcolo della prima e ultima ora, in caso di ingressi ritardati o uscite anticipate
 */
$rb = RBUtilities::getInstance($db);
$current_class = $rb->loadClassFromClassID($classe);
$schedule_module = $current_class->get_modulo_orario();
$d = date("w", strtotime($dati['data']));
$day = $schedule_module->getDay($d);
$starts = $day->getLessonsStartTime();
list($h, $m, $s) = explode(":", $dati['ingresso']);
$ingresso = new RBTime($h, $m, $s);
list($h, $m, $s) = explode(":", $dati['uscita']);
$uscita = new RBTime($h, $m, $s);

$hours = count($starts);
$prima_ora = 1;
$ultima_ora = $hours;

for($i = 1; $i < $hours; $i++){
	if ($ingresso->compare($starts[$i]) == -1){
		$prima_ora = $i -1;
		break;
	}
}

for($i = $hours; $i > 0; $i--){
	if ($uscita->compare($starts[$i]) == 1){
		$ultima_ora = $i;
		break;
	}
}

/* 
 * array di firme, argomenti e id ore
 */
$sel_firme = "SELECT * FROM rb_reg_firme WHERE id_registro = {$dati['id_reg']} ORDER BY ora";
try{
	$res_firme = $db->executeQuery($sel_firme);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
$firme = array();
for($x = $prima_ora; $x <= $ultima_ora; $x++){
	$firme[$x] = array("id" => 0, "mat" => 0, "dmat" => '', "ora" => $x, "id_registro" => $dati['id_reg']);
}
if($res_firme->num_rows > 0){
	while($sig = $res_firme->fetch_assoc()){
		$firme[$sig['ora']]['id'] = $sig['id'];
		$firme[$sig['ora']]['mat'] = $sig['materia'];
		$firme[$sig['ora']]['ora'] = $sig['ora'];
		if ($sig['materia'] != 0){
			$firme[$sig['ora']]['dmat'] = $materie[$sig['materia']];
		}
	}
}
$response['firme'] = $firme;

$res = json_encode($response);
echo $res;
exit;
