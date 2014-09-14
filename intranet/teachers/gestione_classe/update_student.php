<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

function switch_repeating($student, $value, $db){
	$switch = "UPDATE rb_alunni SET ripetente = $value WHERE id_alunno = $student";
	if($db->executeUpdate($switch))
		return true;
	else
		return false;
}

function address($student, $value, $db){
	$sel_add = $db->executeQuery("SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = $student");
	if($sel_add->num_rows > 0){
		$query = "UPDATE rb_indirizzi_alunni SET indirizzo = '$value' WHERE id_alunno = $student";
	}
	else{
		$query = "INSERT INTO rb_indirizzi_alunni (id_alunno, indirizzo) VALUES ($student, '".$value."')";
	}
	try{
		$db->executeUpdate($query);
	} catch (MySQLException $ex){
		echo $ex->getMessage();
		return false;
	}
	return true;
}

function phone($student, $value, $db){
	$sel_add = $db->executeQuery("SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = $student");
	if($sel_add->num_rows > 0){
		$query = "UPDATE rb_indirizzi_alunni SET telefono1 = '$value' WHERE id_alunno = $student";
	}
	else{
		$query = "INSERT INTO rb_indirizzi_alunni (id_alunno, telefono1) VALUES ($student, '".$value."')";
	}
	try{
		$db->executeUpdate($query);
	} catch (MySQLException $ex){
		echo $ex->getMessage();
		return false;
	}
	return true;
}

function upd_date($student, $value, $db){
	$dn = format_date($value, IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$query = "UPDATE rb_alunni SET data_nascita = '$dn' WHERE id_alunno = $student";
	
	try{
		$db->executeUpdate($query);
	} catch (MySQLException $ex){
		echo $ex->getMessage();
		return false;
	}
	return true;
}

header("Content-type: text/plain");

switch($_REQUEST['action']){
	case "switch":
		$student = $_REQUEST['student'];
		$value = $_REQUEST['repeat'];
		$response = switch_repeating($student, $value, $db);
		if($response)
			print ($value == 1) ? "SI" : "NO";
		else 
			print "ko;".$db->error();
		break;
	case "address":
		$student = $_REQUEST['student'];
		$value = $_REQUEST['address'];
		$response = address($student, $value, $db);
		if(!$response)
			print "Errore";
		else
			print("<a href='#' id='row".$student."_field3' onclick='change_address(this, ".$student.")' style='font-weight: normal; color: #303030'>$value</a>");
		break;
	case "phone":
		$student = $_REQUEST['student'];
		$value = $_REQUEST['phone'];
		$response = phone($student, $value, $db);
		if(!$response)
			print "Errore";
		else
			print("<a href='#' id='row".$student."_field4' onclick='change_phone(this, ".$student.")' style='font-weight: normal; color: #303030'>$value</a>");
		break;
	case "date":
		$student = $_REQUEST['student'];
		$value = $_REQUEST['date'];
		$response = upd_date($student, $value, $db);
		if(!$response)
			print "Errore";
		else
			echo "ok";
		break;
	default:
		print "Parametro sconosciuto: ".$_REQUEST['action'];
		break;
}

exit;
