<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch($_REQUEST['action']){
	case "profile":
		$email = trim($_REQUEST['email']);
		if(!check_mail($email)){
			$response['status'] = "komail";
			$response['message'] = "Indirizzo email non valido: ricontrolla";
			echo json_encode($response);
			exit;
		}
		$blog = trim($_REQUEST['blog']);
		$messenger = trim($_REQUEST['mess']);
		$id = $_SESSION['__user__']->getUid();
		try {
			$search_data = $db->execute("SELECT * FROM rb_profili_alunni WHERE id_alunno = $id");
			if($search_data->num_rows > 0){
				$query = "UPDATE rb_profili_alunni SET email = '$email', messenger = ".field_null($messenger, true).", blog = ".field_null($blog, true)." WHERE id_alunno = $id";
			}
			else{
				$query = "INSERT INTO rb_profili_alunni (id_alunno, email, messenger, blog) VALUES ($id, '$email', ".field_null($messenger, true).", ".field_null($blog, true).")";
			}
			$db->executeUpdate($query);
			$response['message'] = "Profilo aggiornato correttamente";
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage()." === ".$ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
	case "address":
		$address = trim($_REQUEST['indirizzo']);
		$phone = trim($_REQUEST['telefono']);
		$cell = trim($_REQUEST['cellulare']);
		$phone2 = trim($_REQUEST['telefono2']);
		$id = $_SESSION['__user__']->getUid();
		try {
			$search_data = $db->execute("SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = $id");
			if($search_data->num_rows > 0){
				$stat = "UPDATE rb_indirizzi_alunni SET indirizzo = ".field_null($address, true).", telefono1 = ".field_null($phone, true).", telefono2 = ".field_null($cell, true).", telefono3 = ".field_null($phone2, true)." WHERE id_alunno = $id";
			}
			else{
				$stat = "INSERT INTO rb_indirizzi_alunni (id_alunno, indirizzo, telefono1, telefono2, telefono3) VALUES ($id, ".field_null($address, true).", ".field_null($phone, true).", ".field_null($cell, true).", ".field_null($phone2, true).")";
			}
			$db->executeUpdate($stat);
			$response['message'] = "Profilo aggiornato correttamente";
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage()." === ".$ex->getQuery();
			echo json_encode($response);
			exit;
		}
		break;
}

echo json_encode($response);
exit;
