<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$search = "";
if(isset($_REQUEST['start']) && ($_REQUEST['start'] != "all")){
	$search = "WHERE cognome LIKE '".$db->real_escape_string($_REQUEST['start'])."%'";
}

$gruppo = 1;
$sel_utenti = null;
if(isset($_REQUEST['gruppo']) && $_REQUEST['gruppo']){
	$gruppo = $_REQUEST['gruppo'];
}
switch($gruppo){
	case 2:
		$sel_utenti = "SELECT id_alunno AS id, cognome, nome FROM rb_alunni {$search} ORDER BY cognome, nome";
		break;
	case 3:
		$sel_utenti = "SELECT rb_utenti.uid AS id, cognome, nome FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND gid = 4 $search ORDER BY cognome, nome";
		break;
	case 1:
	default:
		$sel_utenti = "SELECT rb_utenti.uid AS id, cognome, nome FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND gid <> 4 $search ORDER BY cognome, nome";
		break;
}

$alfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

try{
	$res_utenti = $db->executeQuery($sel_utenti);
} catch (MySQLException $ex){
	$ex->redirect();
}

$navigation_label = "gestione utenti";
$drawer_label = "Modifica password utente";

include "new_pwd.html.php";
