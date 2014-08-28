<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$id_voto = $_REQUEST['id_voto'];
header("Content-type: text/plain");

$sel_voto = "SELECT * FROM rb_voti WHERE id_voto = $id_voto";
try{
	$res_voto = $db->executeQuery($sel_voto);
} catch (MySQLException $ex){
	print ("ko;".$ex->getMessage());
	exit;
}
if($res_voto->num_rows < 1){
	$res = "ko;Parametri errati";
}
else{
	$voto = $res_voto->fetch_assoc();
	$res = "ok;".$voto['voto'].";".$voto['modificatori'].";".$voto['descrizione'].";".$voto['tipologia'].";".$voto['note'].";".format_date($voto['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/").";".$voto['argomento'].";".$voto['alunno'];
}

print $res;
exit;
