<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

$sel_user = "SELECT uid, nome, cognome, username, accessi, permessi FROM rb_utenti WHERE uid = {$_POST['uid']}";
try{
	$res_utenti = $db->execute($sel_user);
} catch(MYSQLException $ex){
	$response['status'] = "kosql";
	$response['query'] = $ex->getQuery();
	$response['dbg_message'] = $ex->getMessage();
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
$utente = $res_utenti->fetch_assoc();

$sel_gr = "SELECT gid FROM rb_gruppi_utente WHERE uid = {$utente['uid']}";
try{
	$groups = $db->executeQuery($sel_gr);
} catch(MYSQLException $ex){
	$response['status'] = "kosql";
	$response['query'] = $ex->getQuery();
	$response['dbg_message'] = $ex->getMessage();
	$response['message'] = "Errore nella registrazione dei dati";
	$res = json_encode($response);
	echo $res;
	exit;
}
$gid = array();
while($group = $groups->fetch_assoc()) {
	$gid[] = $group['gid'];
}
$response['gid'] = $gid;

$user = new SchoolUserBean($utente['uid'], $utente['nome'], $utente['cognome'], $gid, $utente['permessi'], '');

if($user->isAdministrator()){
	$response['admin'] = 1;
}
else {
	$response['admin'] = 0;
}
if($user->isPrimarySchoolAdministrator()){
	$response['psadmin'] = 1;
}
else {
	$response['psadmin'] = 0;
}
if($user->isMiddleSchoolAdministrator()){
	$response['msadmin'] = 1;
}
else {
	$response['msadmin'] = 0;
}

$res = json_encode($response);
echo $res;
exit;
