<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$f = $_POST['file'];
$school_order = $_POST['school_order'];
$rows = file("../../tmp/{$f}");
$ok = $ko = $tot = 0;
$tot = count($rows);
$errs = "";
/* log */
$log_path = "accounts".date("YmdHis").".txt";
$log = fopen("../../tmp/{$log_path}", "w");
foreach($rows as $row){
	if ($school_order == 1){
		list($cognome, $nome, $data, $luogo, $cf, $sex, $rip, $cod_classe) = explode(";", $row);	
		if ($cod_classe != ""){
			$sel_id = "SELECT id_classe FROM rb_classi WHERE ordine_di_scuola = {$school_order} AND anno_corso = ".substr($cod_classe, 0, 1)." AND sezione = '".substr($cod_classe, 1, 1)."'";
			$id_classe = $db->executeCount($sel_id);
		}
	
		if (trim($rip) == "") $rip = 0;
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
		
		$insert = "INSERT INTO rb_alunni (username, password, cognome, nome, data_nascita, luogo_nascita, codice_fiscale, sesso, id_classe, attivo, accessi, ripetente) VALUES ('$username', '$pwd', '$cognome', '$nome', ".field_null($data, true).", ".field_null($luogo, true).", ".field_null($cf, true).", '$sex', ".field_null($id_classe, false).", '1', 0, $rip)";
		try{
			$db->executeUpdate($insert);
			fwrite($log, "{$cognome} {$nome} (".substr($cod_classe, 0, 2)."): {$username}:{$pwd_chiaro}\n");
			$ok++;
		} catch (MySQLException $ex) {
			$ko++;
			$errs .= $ex->getMessage().$ex->getQuery()."<br />";
		}
	}
	else if ($school_order == 2){
		list($cognomenome, $cls, $cf, $dataluogo, $sex) = explode(";", $row);
		list($cognome, $nome) = explode(" ", $cognomenome, 2);
		list($annoc, $sezione) = explode(" ", $cls);
		$sel_id = "SELECT id_classe FROM rb_classi WHERE ordine_di_scuola = {$school_order} AND anno_corso = {$annoc} AND sezione = '{$sezione}'";
		$id_classe = $db->executeCount($sel_id);
		$data = format_date(substr($dataluogo, 0, 10), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$luogo = substr($dataluogo, 11);
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
		$luogo = $db->real_escape_string($luogo);
		
		$insert = "INSERT INTO rb_alunni (username, password, cognome, nome, codice_fiscale, data_nascita, luogo_nascita, sesso, id_classe, attivo, accessi) VALUES ('{$username}', '{$pwd}', '{$cognome}', '{$nome}', NULL, '{$data}', '{$luogo}', '$sex', $id_classe, '1', 0)";
		try{
			$db->executeUpdate($insert);
			//echo $insert;
			fwrite($log, $insert."=>{$pwd_chiaro}\n");
			$ok++;
		} catch (MySQLException $ex) {
			$ko++;
			$errs .= $ex->getMessage().$ex->getQuery()."<br />";
		}
	}
}
fclose($log);
echo "ok;{$ok};{$tot};{$ko};{$errs};{$log_path};tmp;";
exit;
