<?php

require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";
require_once '../../lib/RBUtilities.php';

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$id_religione = 26;
if ($ordine_scuola == 2){
	$id_religione = 30;
}
	
switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$note_time = "AND data <= NOW()";
		$label = "Medie voto totali al ".date("d/m/Y");
		break;
	case 1:
		$int_time = "AND data_voto <= '{$fine_q}'";
		$note_time = "AND data <= '{$fine_q}'";
		$label = "Medie voto primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '{$fine_q}' AND data_voto <= NOW()) ";
		$note_time = "AND (data > '{$fine_q}' AND data <= NOW()) ";
		$label = "Medie voto secondo quadrimestre";
}

$studente = $_SESSION['__user__']->getUid();
if(isset($_REQUEST['ric']) && ($_REQUEST['ric'] == "genitori")){
	$studente = $_SESSION['__current_son__'];
}

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$sel_marks = "(SELECT data_voto AS data, voto, modificatori, tipologia, descrizione FROM rb_voti WHERE materia = {$_REQUEST['subjectID']} AND anno = ".$_SESSION['__current_year__']->get_ID()." AND alunno = ".$studente." AND privato = 0 $int_time ORDER BY data_voto DESC)";
$sel_marks .=  " UNION (SELECT data AS data, 'impreparato' AS voto, '' AS modificatori, 2 AS tipologia, 'Interrogazione' AS descizione FROM rb_note_didattiche WHERE tipo = 1 AND materia = {$_REQUEST['subjectID']} AND anno = ".$_SESSION['__current_year__']->get_ID()." AND alunno = ".$studente." $note_time ORDER BY data  DESC)";
try{
	$res_marks = $db->execute($sel_marks);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage()." === ".$ex->getQuery();
	echo json_encode($response);
	exit;
}
$values = array();
$rows = array();
$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
$response['numero_voti'] = $res_marks->num_rows;

while($row = $res_marks->fetch_assoc()){
	if ($_REQUEST['subjectID'] == $id_religione){
		$row['voto'] = $voti_religione[RBUtilities::convertReligionGrade($row['voto'])];
	}
	$ar = array("date" => $row['data'], "data" => format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), "voto" => $row['voto'], "mod" => $row['modificatori'], "tipologia" => $row['tipologia'], "desc" => utf8_decode($row['descrizione']));
	array_push($rows, $ar);
}
$msarray = new ArrayMultiSort($rows);
$msarray->setSortFields(array("date"));
$msarray->sort();
$ordered_grades = $msarray->getData();

$response['voti'] = $ordered_grades;

echo json_encode($response);
exit;
