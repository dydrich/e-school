<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

header("Content-type: text/plain");

if($_REQUEST['step'] == 1){
	$last_name = strtoupper($_REQUEST['name']);
	if($_REQUEST['sex'] == "all")
		$sex = "";
	else
		$sex = "AND sesso = '".$_REQUEST['sex']."'";
	if($_REQUEST['rip'] == "all")
		$rip = "";
	else 
		$rip = "AND ripetente = ".$_REQUEST['rip'];
	if($_REQUEST['h'] == "0")
		$h = "";
	else if($_REQUEST['h'] == "1")
		$h = "AND H = 1";
	else if($_REQUEST['h'] == "2")
		$h = "AND H > 3";
	else 
		$h = "AND H > 0";
	if($_REQUEST['from'] == "0")
		$from = "";
	else
		$from = "AND classe_provenienza = ".$_REQUEST['from'];
	switch($_REQUEST['grade']){
		case "1":
			$grade = "AND voto < 5";
			break;
		case "2":
			$grade = "AND (voto >= 5 AND voto < 6)";
			break;
		case "3":
			$grade = "AND (voto >= 6 AND voto < 7)";
			break;
		case "4":
			$grade = "AND (voto >= 6 AND voto < 9)";
			break;
		case "5":
			$grade = "AND voto > 8.5";
			break;
		case "0":
		default:
				$grade = "";
				break;
	}
	$_SESSION['__query_students__'] = "SELECT id_alunno, cognome, nome, voto FROM fc_alunni WHERE id_classe IS NULL $last_name $sex $rip $h $from $grade ORDER BY cognome, nome";
	$query_count = "SELECT COUNT(id_alunno) FROM fc_alunni WHERE id_classe IS NULL $last_name $sex $rip $h $from $grade";
	$count = $db->executeCount($query_count);
	
	print "ok|$count|$query_count";
	exit;
}
else if($_REQUEST['step'] == 2){
	try{
		$stds = $db->executeQuery($_SESSION['__query_students__']);
	} catch (MySQLException $ex){
		print "ko|".$ex->getMessage();
		exit;
	}
	$students = array();
	while($st = $stds->fetch_assoc()){
		$row = $st['cognome']." ".$st['nome'].";".$st['id_alunno'].";".$st['voto'];
		array_push($students, $row);
	}
	$out = join("|", $students);
	print "ok|$out";
	exit;
}
