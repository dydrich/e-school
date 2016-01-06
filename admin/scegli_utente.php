<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$search = "";
if(isset($_REQUEST['start']) && ($_REQUEST['start'] != "all")){
	$search = "WHERE cognome LIKE '".$db->real_escape_string($_REQUEST['start'])."%'";
}

$gruppo = 3;
$sel_utenti = null;
if(isset($_REQUEST['gruppo']) && $_REQUEST['gruppo']){
	$gruppo = $_REQUEST['gruppo'];
}

switch($gruppo){
	case 2:
		$sel_utenti = "SELECT id_alunno AS id, cognome, nome FROM rb_alunni {$search} {$params} ORDER BY cognome, nome";
		break;
	case 3:
		$sel_utenti = "SELECT rb_utenti.uid AS id, cognome, nome FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND gid <> 4 $search ORDER BY cognome, nome";
		break;
	case 1:
	default:
		$sel_utenti = "SELECT rb_utenti.uid AS id, cognome, nome FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND gid = 4 $search ORDER BY cognome, nome";
		break;
}

if (isset($_REQUEST['school_order'])) {
	switch($gruppo){
		case 3:
			$sel_utenti = "SELECT rb_utenti.uid AS id, cognome, nome FROM rb_utenti, rb_gruppi_utente, rb_docenti WHERE rb_utenti.uid = rb_gruppi_utente.uid AND rb_utenti.uid = id_docente AND gid = 2 AND tipologia_scuola = {$_REQUEST['school_order']} $search ORDER BY cognome, nome";
			break;
		case 1:
			$sel_utenti = "SELECT rb_utenti.uid AS id, rb_utenti.cognome, rb_utenti.nome
							FROM rb_utenti, rb_gruppi_utente, rb_genitori_figli, rb_alunni, rb_classi
							WHERE rb_utenti.uid = rb_gruppi_utente.uid
							AND gid = 4
							AND rb_utenti.uid = rb_genitori_figli.id_genitore
							AND rb_genitori_figli.id_alunno = rb_alunni.id_alunno
							AND rb_alunni.id_classe = rb_classi.id_classe aND rb_classi.ordine_di_scuola = {$_REQUEST['school_order']} $search ORDER BY rb_utenti.cognome, rb_utenti.nome";
			break;
	}
}

$alfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

try{
	$res_utenti = $db->executeQuery($sel_utenti);
} catch (MySQLException $ex){
	$ex->redirect();
}

$navigation_label = "sviluppo";
$drawer_label = "Cambia utente";

include "scegli_utente.html.php";
