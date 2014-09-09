<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "");

$perms = explode(",", $_REQUEST['perms']);

$birthday = $_REQUEST['birthday']."#".$perms[1];
$address = $_REQUEST['address']."#".$perms[2];
$phone = $_REQUEST['phone']."#".$perms[3];
$cellphone = $_REQUEST['cellphone']."#".$perms[4];
$email = $_REQUEST['email']."#".$perms[5];
$messenger = $_REQUEST['messenger']."#".$perms[6];
$website = $_REQUEST['web']."#".$perms[7];
$blog = $_REQUEST['blog']."#".$perms[8];

try{
	// check if record exists
	$exs = $db->executeQuery("SELECT * FROM rb_profili WHERE id = ".$_SESSION['__user__']->getUid());
	if($exs->num_rows > 0){
		$query = "UPDATE rb_profili SET data_nascita = '$birthday', indirizzo = '$address', telefono = '$phone', cellulare = '$cellphone', email = '$email', messenger = '$messenger', web = '$website', blog = '$blog' WHERE id = ".$_SESSION['__user__']->getUid();
	}
	else{
		$query = "INSERT INTO rb_profili VALUES (".$_SESSION['__user__']->getUid().", '$birthday', '$address', '$phone', '$cellphone', '$email', '$messenger', '$website', '$blog')";
	}
	$upd = $db->executeQuery($query);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = "Si è verificato un errore";
	$response['dbg_message'] = $ex->getQuery()."=>".$ex->getMessage();
	$res = json_encode($response);
	echo $res;
	exit;
}

$_SESSION['__user__']->setBirthday($birthday);
$_SESSION['__user__']->setAddress($address);
$_SESSION['__user__']->setPhone($phone);
$_SESSION['__user__']->setMobile($cellphone);
$_SESSION['__user__']->setEmail($email);
$_SESSION['__user__']->setMessenger($messenger);
$_SESSION['__user__']->setWeb($website);
$_SESSION['__user__']->setBlog($blog);

$res = json_encode($response);
echo $res;
exit;
