<?php

include "../lib/start.php";

check_session();

$id_impegno = $_POST['id_impegno'];
$tipo_impegno = $_POST['tipo'];

header("Content-type: text/plain");

$sel_desc = "SELECT descrizione, note FROM rb_impegni WHERE id_impegno = $id_impegno";
try{
	$res_desc = $db->executeQuery($sel_desc);
} catch (MySQLException $ex){
	print ("ko|".$ex->getMessage());
	exit;
}
$desc = $res_desc->fetch_assoc();
$res = "ok|".$desc['descrizione']."|".$desc['note'];

print $res;
exit;

?>