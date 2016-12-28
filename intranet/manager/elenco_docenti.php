<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$drawer_label = "Elenco docenti ";
$navigation_label = "";
switch($_SESSION['__school_order__']) {
	case 1:
		$navigation_label .= "scuola secondaria";
		break;
	case 2:
		$navigation_label .= "scuola primaria";
		break;
}

$order = "name";
$param_order = "rb_utenti.cognome, rb_utenti.nome";
if(isset($_REQUEST['order'])){
	$order = $_REQUEST['order'];
	if($_REQUEST['order'] == "subject"){
		$param_order = "rb_materie.materia, rb_utenti.cognome, rb_utenti.nome";
	}
}

$school_order = "";
if ($_SESSION['__school_order__']){
	$school_order = "AND rb_docenti.tipologia_scuola = {$_SESSION['__school_order__']}";
}

$sel_docenti = "SELECT rb_utenti.uid, rb_utenti.nome, rb_utenti.cognome, rb_docenti.*, rb_materie.materia, rb_materie.id_materia FROM rb_docenti, rb_utenti, rb_materie WHERE rb_utenti.uid = rb_docenti.id_docente AND rb_docenti.materia = rb_materie.id_materia {$school_order} ORDER BY $param_order";
$res_docenti = $db->execute($sel_docenti);
//print $sel_links;
$count = $res_docenti->num_rows;
$_SESSION['count_teac'] = $count;

if($r_ext > $_SESSION['count_teac']){
	$r_ext = $_SESSION['count_teac'];
}

$res = $db->executeQuery("SELECT id_utente, valore FROM rb_parametri_utente WHERE id_parametro = 4");
$meetings = [];
if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {
		$meetings[$row['id_utente']] = $row['valore'];
	}
}

include "elenco_docenti.html.php";
