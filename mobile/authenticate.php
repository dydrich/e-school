<?php

include "../lib/start.php";
header("Content-type: application/json");
$sel_user = "SELECT uid, nome, cognome, gruppi, username, accessi, permessi FROM utenti WHERE username = '".trim($_REQUEST['nick'])."' AND password = '".md5(trim($_REQUEST['pass']))."'";
$_SESSION['sel'] = $sel_user;
try{
	$res_user = $db->executeQuery($sel_user);
} catch(MySQLException $ex){
	$er = "ko;".$ex->getQuery().";".$ex->getMessage();
	print $er;
	exit;
}
$exist = $res_user->num_rows;
if($exist < 1){
	$res_user->free();
	print("ko");
	exit;
}

$user = $res_user->fetch_array();
$token = hash("md5", $user['nome']." ".$user['cognome'].$user['uid']);

$sel_classes = "SELECT classi.* FROM classi, cdc WHERE classi.id_classe = cdc.id_classe AND cdc.id_docente = ".$user['uid']." ORDER BY sezione, anno_corso";
print $sel_classes;
try{
    $res_classes = $db->executeQuery($sel_classes);
} catch(MySQLException $ex){
    $er = "ko;".$ex->getQuery().";".$ex->getMessage();
    print $er;
    exit;
}
$json = "{'token' : '$token', 'name' : '".$user['nome']." ".$user['cognome']."', 'uid': '".$user['uid']."', 'classes': {";
while($cls = $res_classes->fetch_array()){
    $st = "{'id': '".$cls['id_classe']."', '_class': '".$cls['anno_corso'].$cls['sezione']."'},";
    $json .= $st;
}
$json = substr($json, 0, strlen($json) - 1);

$json .= "}}";
print $json;
exit;

?>