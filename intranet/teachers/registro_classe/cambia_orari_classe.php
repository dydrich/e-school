<?php

require_once "../../../lib/start.php";

check_session();

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

$campo = $_POST['field'];
$value = $_POST['value'];
if(!check_time($value)){
	$response['status'] = "ko";
	$response['message'] = "Formato orario non valido";
	$res = json_encode($response);
	echo $res;
	exit;
}
$update = "UPDATE rb_reg_classi SET $campo = '".$value."' WHERE id_reg = ".$_SESSION['registro']['id_reg'];
$r_upd = $db->executeUpdate($update);

$upd_alunni = "UPDATE rb_reg_alunni SET $campo = '".$value."' WHERE $campo IS NOT NULL AND id_registro = ".$_SESSION['registro']['id_reg'];
$_SESSION['query'] = $upd_alunni;
$r_upd_alunni = $db->executeUpdate($upd_alunni);

$res = json_encode($response);
echo $res;
exit;