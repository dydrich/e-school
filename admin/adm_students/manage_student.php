<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

header("Content-type: text/plain");

$nome = $db->real_escape_string($_REQUEST['fname']);
$cognome = $db->real_escape_string($_REQUEST['lname']);
$sex = $_REQUEST['sex'];
$cls = $_REQUEST['cls'];
$school_order = $_POST['school_order'];
$data_nascita = format_date($_REQUEST['data_nascita'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$luogo_nascita = $db->real_escape_string($_REQUEST['luogo_nascita']);

$log_file = "{$school_order}account_studenti".date("Ymd").".txt";
$log = fopen("../../tmp/{$log_file}", "a");

$names = array();
$sel_usernames = "SELECT username FROM rb_alunni";
$res_usernames = $db->executeQuery($sel_usernames);
while($row = $res_usernames->fetch_assoc()){
	$names[] = $row['username'];
}

$username = get_login($names, $nome, $cognome);
$pwd_chiaro = "";
$pwd = get_password(strtolower($nome), strtolower($cognome), $pwd_chiaro);
$names[] = $username;

$cognome = $db->real_escape_string($cognome);
$nome = $db->real_escape_string($nome);

$insert = "INSERT INTO rb_alunni (username, password, cognome, nome, data_nascita, luogo_nascita, codice_fiscale, sesso, id_classe, attivo, accessi) VALUES ('$username', '$pwd', '$cognome', '$nome', ".field_null($data_nascita, true).", ".field_null($luogo_nascita, true).",  NULL, '$sex', $cls, '1', 0)";
try{
	$uid = $db->executeUpdate($insert);
	if (is_installed("com")) {
		$db->executeUpdate("INSERT INTO rb_com_users (uid, table_name, type) VALUES ({$uid}, 'rb_alunni', 'student')");
	}
	fwrite($log, "{$cognome} {$nome}:{$username}:{$pwd_chiaro}\n");
} catch (MySQLException $ex){
	print "kosql|".$ex->getMessage()."|".$ex->getQuery();
	fclose($log);
	exit;
}
fclose($log);
print "ok";
exit;
