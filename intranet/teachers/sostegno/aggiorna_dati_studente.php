<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

switch ($_POST['area']) {
	case "nucleo":
		$father = $db->real_escape_string($_POST['father']);
		$mother = $db->real_escape_string($_POST['mother']);
		$brot = $db->real_escape_string($_POST['brot']);
		$other = $db->real_escape_string($_POST['oth']);
		if ($_POST['idd'] == 0){
			$statement = "INSERT INTO rb_dati_sostegno (alunno, padre, madre, fratelli_sorelle, altro) VALUES ({$_SESSION['__sp_student__']['alunno']}, '{$father}', '{$mother}', '{$brot}', '{$other}')";
		}
		else {
			$statement = "UPDATE rb_dati_sostegno SET padre = '{$father}', madre = '{$mother}', fratelli_sorelle = '{$brot}', altro = '{$other}' WHERE alunno = {$_SESSION['__sp_student__']['alunno']}";
		}
		break;
	case "scuola":
		$school = $db->real_escape_string($_POST['school']);
		$class = $db->real_escape_string($_POST['class']);
		if ($_POST['idd'] == 0){
			$statement = "INSERT INTO rb_dati_sostegno (alunno, scuola_provenienza, classe_provenienza) VALUES ({$_SESSION['__sp_student__']['alunno']}, '{$school}', '{$class}')";
		}
		else {
			$statement = "UPDATE rb_dati_sostegno SET scuola_provenienza = '{$school}', classe_provenienza = '{$class}' WHERE alunno = {$_SESSION['__sp_student__']['alunno']}";
		}
		break;
	case "profilo":
		$diff = $db->real_escape_string($_POST['diff']);
		$profile = $db->real_escape_string($_POST['profile']);
		if ($_POST['idd'] == 0){
			$statement = "INSERT INTO rb_dati_sostegno (alunno, difficolta_prevalenti, profilo) VALUES ({$_SESSION['__sp_student__']['alunno']}, '{$diff}', '{$profile}')";
		}
		else {
			$statement = "UPDATE rb_dati_sostegno SET difficolta_prevalenti = '{$diff}', profilo = '{$profile}' WHERE alunno = {$_SESSION['__sp_student__']['alunno']}";
		}
		break;
	case "diagnosi":
		//$diagnosi = $db->real_escape_string($_POST['diagnosi']);
		$terapia = $db->real_escape_string($_POST['terapia']);
		if ($terapia == 1){
			$str = array("orto" => 0, "psico" => 0, "moto" => 0, "neuro" => 0, "oth" => "");
			if(isset($_POST['orto']))
				$str['orto'] = 1;
			if(isset($_POST['psico']))
				$str['psico'] = 1;
			if(isset($_POST['moto']))
				$str['moto'] = 1;
			if(isset($_POST['neuro']))
				$str['neuro'] = 1;
			if (isset($_POST['oth'])){
				$str['oth'] = $_POST['oth'];
				$ins = implode("#", $str);
			}
		}
		if ($_POST['idd'] == 0){
			$statement = "INSERT INTO rb_dati_sostegno (alunno, terapia, tipo_terapia) VALUES ({$_SESSION['__sp_student__']['alunno']}, {$terapia}, '{$ins}')";
		}
		else {
			$statement = "UPDATE rb_dati_sostegno SET terapia = '{$terapia}', tipo_terapia = '{$ins}' WHERE alunno = {$_SESSION['__sp_student__']['alunno']}";
		}
		break;
	case "attivita":
		$id = $_POST['id'];
		$att = $db->real_escape_string($_POST['att']);
		$dt = format_date($_POST['day'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		if ($_POST['id'] == 0){
			$statement = "INSERT INTO rb_attivita_sostegno (alunno, data, anno, attivita) VALUES ({$_SESSION['__sp_student__']['alunno']}, '{$dt}', {$_SESSION['__current_year__']->get_ID()}, '{$att}')";
		}
		else {
			$statement = "UPDATE rb_attivita_sostegno SET data = '{$dt}', attivita = '{$att}' WHERE id = {$id}";
		}
		break;
	case "delatt":
		$id = $_POST['id'];
		$statement = "DELETE FROM rb_attivita_sostegno WHERE id = {$id}";
		break;
}

$response = array("status", "ok", "message" => "Dati aggiornati correttamente");
header("Content-type: application/json");

try {
	$res = $db->executeUpdate($statement);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['query'] = $ex->getQuery();
	$response['message'] = $ex->getMessage();
	echo json_encode($response);
	exit;
}

$sel_dati = "SELECT * FROM rb_dati_sostegno WHERE alunno = {$_SESSION['__sp_student__']['alunno']}";
$res_dati = $db->execute($sel_dati);
$dati = $res_dati->fetch_assoc();
$_SESSION['__sp_student__']['dati'] = $dati;
echo json_encode($response);
