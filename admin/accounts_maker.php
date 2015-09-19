<?php

require_once "../lib/start.php";
require_once "../lib/AccountManager.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Attivazione completata");

class Alunno{
	var $id_alunno;
    var $username;
    var $password;
    var $nome;
    var $cognome;
    var $clear_pwd; // pwd in chiaro
    
    function Alunno($dati){
        $this->id_alunno = $dati['id_alunno'];
        $this->username = trim($dati['username']);
        $this->password = $dati['password'];
        $this->nome = trim($dati['nome']);
        $this->cognome = $dati['cognome'];
    }
}

$alunni = array();
// file di log
$log_file = fopen("../tmp/studenti".date("Ymd"), "w+");

$index = 0;
$incompleti = 0;
$sel_names = "SELECT username FROM rb_alunni WHERE username IS NOT NULL";
try{
    $res_names = $db->executeQuery($sel_names);
} catch (MySQLException $ex){
    $response['status'] = "kosql";
    $response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
    $response['message'] = "Errore nella registrazione dei dati";
    fclose($log_file);
    $res = json_encode($response);
    echo $res;
    exit;
}
$names = array();
while($name = $res_names->fetch_assoc()) {
    $names[] = $name;
}
$sel = "SELECT id_alunno, nome, cognome, username, password FROM rb_alunni WHERE attivo = 1";
if (isset($_REQUEST['notcomplete']) && $_REQUEST['notcomplete'] == 1) {
    $sel .= " AND username IS NULL";
}
fwrite($log_file, $sel."\n\n");
try{
    $res = $db->execute($sel);
} catch (MySQLException $ex){
    $response['status'] = "kosql";
    $response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
    $response['message'] = "Errore nella registrazione dei dati";
    fclose($log_file);
    $res = json_encode($response);
    echo $res;
    exit;
}
while($_alunno = $res->fetch_assoc()){
	if($_alunno['username'] == ''){
		$al = new Alunno($_alunno);
		$incompleti++;
		$al->username = AccountManager::generateLogin($names, $al->nome, $al->cognome);
        $pwd = AccountManager::generatePassword();
		$al->password = $pwd['e'];
		//print ($al->username).":".$al->password."<br/>";
		$upd = "UPDATE rb_alunni SET username = '".$al->username."', password = '".$al->password."' WHERE id_alunno = ".$al->id_alunno;
		$r = $db->execute($upd);
		$str_log = $al->nome." ".$al->cognome."=>".$al->username."::".$pwd['c']."\n";
		fwrite($log_file, $str_log);
	}
}
fclose($log_file);
$res = json_encode($response);
echo $res;
exit;
