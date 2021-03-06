<?php

require_once "../../lib/start.php";
require_once "../../lib/AccountManager.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

/**
 * getSexFromCF
 *
 * @desc restituisce il sesso a partire dal codice fiscale
 * @param string (codice fiscale)
 * @return string (sesso)
 *
 */
function getSexFromCF($cf) {
	$data = intval(substr($cf, 9, 2));
	if ($data < 40) {
		return 'M';
	}
	else {
		return 'F';
	}
}

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

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
			$uid = $db->executeUpdate($insert);
			if (is_installed("messenger")) {
				$db->executeUpdate("INSERT INTO rb_com_users (uid, table_name, type) VALUES ({$uid}, 'rb_alunni', 'student')");
			}
			fwrite($log, "{$cognome} {$nome} (".substr($cod_classe, 0, 2)."): {$username}:{$pwd_chiaro}\n");
			$ok++;
		} catch (MySQLException $ex) {
			$ko++;
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
		}
	}
	else if ($school_order == 2){
		list($cognome, $nome, $cf, $data, $luogo, $cls) = explode(";", $row);
		/*
        $annoc = substr($cls, 0, 1);
		$sezione = substr($cls, 1, 1);
		$sel_id = "SELECT id_classe FROM rb_classi WHERE ordine_di_scuola = {$school_order} AND anno_corso = {$annoc} AND sezione = '{$sezione}' AND anno_scolastico = ".$_SESSION['__current_year__']->get_ID();
		$id_classe = $db->executeCount($sel_id);
		$data = format_date(substr($dataluogo, 0, 10), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$luogo = substr($dataluogo, 11);
		*/
		$names = array();
		$sel_usernames = "SELECT username FROM rb_alunni";
		$res_usernames = $db->executeQuery($sel_usernames);
		while($row = $res_usernames->fetch_assoc()){
			$names[] = $row['username'];
		}

		$sex = getSexFromCF($cf);
		
		$username = AccountManager::generateLogin($names, $nome, $cognome);
		$pwd_chiaro = "";
		$pwd = AccountManager::generatePassword();
		$names[] = $username;
		
		$cognome = trim($db->real_escape_string($cognome));
		$nome = trim($db->real_escape_string($nome));
		$luogo = trim($db->real_escape_string($luogo));
		
		$insert = "INSERT INTO rb_alunni (username, password, cognome, nome, codice_fiscale, data_nascita, luogo_nascita, sesso, id_classe, attivo, accessi)
				   VALUES ('{$username}', '{$pwd['e']}', '{$cognome}', '{$nome}', '{$cf}', '{$data}', '{$luogo}', '$sex', $cls, '1', 0)";
		try{
			$uid = $db->executeUpdate($insert);
			if (is_installed("messenger")) {
				$db->executeUpdate("INSERT INTO rb_com_users (uid, table_name, type) VALUES ({$uid}, 'rb_alunni', 'student')");
			}
			//echo $insert;
			fwrite($log, $insert."=>{$pwd['c']}\n");
			$ok++;
		} catch (MySQLException $ex) {
			$ko++;
			$response['status'] = "kosql";
			$response['message'] = "Operazione non completata a causa di un errore";
			$response['dbg_message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
		}
	}
}
fclose($log);

$response['ok'] = $ok;
$response['tot'] = $tot;
$response['ko'] = $ko;
$response['log_path'] = $log_path;

echo json_encode($response);
exit;
