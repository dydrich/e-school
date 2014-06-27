<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$id_alunno = $_REQUEST['stid'];
$fname = strtoupper($_REQUEST['fname']);
$lname = strtoupper($_REQUEST['lname']);
header("Content-type: text/plain");

$update = "UPDATE rb_alunni SET nome = '$fname', cognome = '$lname' WHERE id_alunno = $id_alunno";
try{
	$res_voto = $db->executeUpdate($update);
} catch (MySQLException $ex){
	print ("ko#".$ex->getMessage()."#".$ex->getQuery());
	exit;
}

$res = "ok#".$lname." ".$fname;

print $res;
exit;

?>