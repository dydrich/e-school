<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

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
$sel = "SELECT id_alunno, nome, cognome, username, password FROM rb_alunni WHERE attivo = 1";
$res = $db->execute($sel);
while($_alunno = $res->fetch_assoc()){
	if($_alunno['username'] == ''){
		$al = new Alunno($_alunno);
		$incompleti++;
		$al->username = get_login($db, $al->nome, $al->cognome);
		$al->password = get_password($al->nome, $al->cognome, &$al->clear_pwd);
		//print ($al->username).":".$al->password."<br/>";
		$upd = "UPDATE rb_alunni SET username = '".$al->username."', password = '".$al->password."' WHERE id_alunno = ".$al->id_alunno;
		$r = $db->execute($upd);
		$str_log = $al->nome." ".$al->cognome.":".$al->username.":".$al->clean_pwd."\n";
		fwrite($log_file, $str_log);
	}
}