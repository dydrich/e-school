<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

header("Content-type: text/plain");

function save_profile($request, $error, $db){
	$email = trim($request['email']);
	if(!check_mail($email)){
		$error = true;
		return "Indirizzo email non valido: ricontrolla";
	}
	$blog = trim($request['blog']);
	$messenger = trim($request['mess']);
	$id = $_SESSION['__user__']->getUid();
	$search_data = $db->execute("SELECT * FROM rb_profili_alunni WHERE id_alunno = $id");
	if($search_data->num_rows > 0){
		return "UPDATE rb_profili_alunni SET email = '$email', messenger = ".field_null($messenger, true).", blog = ".field_null($blog, true)." WHERE id_alunno = $id";
	}
	else{
		return "INSERT INTO rb_profili_alunni (id_alunno, email, messenger, blog) VALUES ($id, '$email', ".field_null($messenger, true).", ".field_null($blog, true).")";
	}
}

function save_address($request, $error, $db){
	$address = trim($request['indirizzo']);
	$phone = trim($request['telefono']);
	$cell = trim($request['cellulare']);
	$phone2 = trim($request['telefono2']);
	$id = $_SESSION['__user__']->getUid();
	$search_data = $db->execute("SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = $id");
	if($search_data->num_rows > 0){
		return "UPDATE rb_indirizzi_alunni SET indirizzo = ".field_null($address, true).", telefono1 = ".field_null($phone, true).", telefono2 = ".field_null($cell, true).", telefono3 = ".field_null($phone2, true)." WHERE id_alunno = $id";
	}
	else{
		return "INSERT INTO rb_indirizzi_alunni (id_alunno, indirizzo, telefono1, telefono2, telefono3) VALUES ($id, ".field_null($address, true).", ".field_null($phone, true).", ".field_null($cell, true).", ".field_null($phone2, true).")";
	}
}

$error = false;
switch($_REQUEST['action']){
	case "profile":
		$query = save_profile($_REQUEST, &$error, $db);
		break;
	case "address":
		$query = save_address($_REQUEST, &$error, $db);
		break;
}

if($error){
	$message = $query;
	print "ko|{$message}";
	exit;
}
else{
	if(!$res = $db->execute($query)){
		$error = true;
		$message = $db->error();
		print "ko|{$message}";
		exit;
	}
	else{
		$message = "Profilo aggiornato correttamente";
		print "ok|{$message}";
		exit;
	}
}
