<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

header("Content-type: text/plain");

if($_REQUEST['action'] != 3){
	$fname = strtoupper($_REQUEST['fname']);
	$lname = strtoupper($_REQUEST['lname']);
	$from = $_REQUEST['from'];
	$sex = $_REQUEST['sex'];
	$h = $_REQUEST['h'];
	if($h > 1)
		$diagnose = $_REQUEST['diagnose'];
	else
		$diagnose = "";
	$grade = $_REQUEST['grade'];
	$note = $_REQUEST['note'];
	
	/* controllo ripetente */
	$sel_school_from = "SELECT id_scuola FROM fc_classi_provenienza WHERE id_classe = $from";
	$school_from = $db->executeCount($sel_school_from);
	if($school_from == 5)
		$ripetente = 1;
	else
		$ripetente = 0;
}



switch($_REQUEST['action']){
	case "1":
		$query = "UPDATE fc_alunni SET nome = '$fname', cognome = '$lname', sesso = '$sex', classe_provenienza = $from, H = $h, diagnosi_h = ".field_null($diagnose, true).", voto = $grade, note = ".field_null(utf8_encode($note), true)." WHERE id_alunno = ".$_REQUEST['stid'];
		break;
	case "2":
		$query = "INSERT INTO fc_alunni (nome, cognome, sesso, ripetente, classe_provenienza, h, diagnosi_h, voto, note) VALUES ('$fname', '$lname', '$sex', $ripetente, $from, $h, ".field_null($diagnose, true).", $grade, ".field_null(utf8_encode($note), true).")";
		break;
	case "3":
		$query = "DELETE FROM fc_alunni WHERE id_alunno = ".$_REQUEST['stid'];
		break;
}
try{
	$db->executeUpdate($query);
} catch (MySQLException $ex){
	print "ko|".$ex->getMessage()."|".$ex->getQuery();
	exit;
}
print "ok";
exit;

?>
