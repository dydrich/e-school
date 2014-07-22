<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$sel_user = "SELECT uid, nome, cognome, username, accessi, permessi FROM rb_utenti WHERE uid = {$_POST['uid']}";
try{
	$res_utenti = $db->execute($sel_user);
} catch(MYSQLException $ex){
	echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
}
$utente = $res_utenti->fetch_assoc();

$sel_gr = "SELECT gid FROM rb_gruppi_utente WHERE uid = {$utente['uid']}";
try{
	$groups = $db->executeQuery($sel_gr);
} catch(MYSQLException $ex){
	echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
}
$gid = array();
while($group = $groups->fetch_assoc()) {
	$gid[] = $group['gid'];
}
$str_groups = join(",", $gid);

$user = new SchoolUserBean($utente['uid'], $utente['nome'], $utente['cognome'], $gid, $utente['permessi'], '');

$out = $str_groups."#";
if($user->isAdministrator()){
	$out .= "1#";
}
else {
	$out .= "0#";
}
if($user->isPrimarySchoolAdministrator()){
	$out .= "1#";
}
else {
	$out .= "0#";
}
if($user->isMiddleSchoolAdministrator()){
	$out .= "1#";
}
else {
	$out .= "0#";
}

echo $out;
exit;
