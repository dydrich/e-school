<?php

include "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$sel_uffici = "SELECT * FROM rb_w_uffici";
$res_uffici = $db->executeQuery($sel_uffici);

if(isset($_REQUEST['id'])){
	$sel = "SELECT id_step, descrizione, ufficio, nome FROM rb_w_uffici, rb_w_step WHERE ufficio = id_ufficio AND id_step = ".$_REQUEST['id'];
	try{
		$res = $db->executeQuery($sel);
	} catch (MySQLException $ex){
    	$ex->alert();
		exit;
	}
	$step = $res->fetch_assoc();
	$_i = $_REQUEST['id'];
}
else{
	$_i = 0;
}

include "dettaglio_step.html.php";

?>