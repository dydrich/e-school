<?php

include "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

//$log = fopen("/tmp/mysql.log", "w+");

if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "update")){
	$campo = $_POST['campo'];
	$value = $_POST['value'];
	$id_ufficio = preg_replace("/[a-zA-Z]/", "", $campo);
	$update = "UPDATE rb_w_uffici SET nome = '".$value."' WHERE id_ufficio = ".$id_ufficio;
	try{
		$r_upd = $db->executeUpdate($update);
	} catch (MySQLException $ex){
		$ex->alert();
		exit;
	}
	$msg = 3;
}
else if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "insert")){
	$sel_max = "SELECT MAX(codice_permessi) AS max FROM rb_w_uffici";
	$_max = $db->executeCount($sel_max);
	$max = $_max*2;
	if($max == 0)
		$max = 1;
	$nome = $_REQUEST['nome'];
	$insert = "INSERT INTO rb_w_uffici (nome, codice_permessi) VALUES ('".$nome."', $max)";
	try{
		$r_ins = $db->executeUpdate($insert);
	} catch (MySQLException $ex){
		$ex->alert();
		exit;
	}
	$msg = 1;
}
else if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "delete")){
	$id = $_REQUEST['id'];
	$delete = "DELETE FROM rb_w_uffici WHERE id_ufficio = $id";
	try{
		$r_del = $db->executeUpdate($delete);
	} catch (MySQLException $ex){
		$ex->alert();
		exit;
	}
	$msg = 2;
}

$sel_uffici = "SELECT * FROM rb_w_uffici ORDER BY id_ufficio DESC";
$res_uffici = $db->executeQuery($sel_uffici);
$array_id = array();
while($ufficio = $res_uffici->fetch_assoc()){
    array_push($array_id, $ufficio['id_ufficio']);
}
$stringa_uffici = join(",", $array_id);

include "uffici.html.php";

?>