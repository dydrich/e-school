<?php

include "../lib/start.php";

header("Content-type: text/plain");

$uname = $db->real_escape_string(trim($_POST['nick']));
$table = $db->real_escape_string($_REQUEST['table']);
$id = $_REQUEST['id'];

$field = "";
if("rb_genitori" == $table) {
	$field = "uid";
}
else if ("rb_utenti" == $table) {
	$field = "uid";
}
else if ("rb_alunni" == $table) {
	$field = "id_alunno";
}

$sel_uname = "SELECT $field FROM $table WHERE username = '$uname' AND $field <> $id";
try{
	$res_uname = $db->executeQuery($sel_uname);
} catch (MySQLException $ex) {
	print "ko|".$ex->getMessage()."|".$ex->getQuery();
	exit;
}
if($res_uname->num_rows > 0)
	print "ok|1";
else
	print "ok|0";
exit;

?>