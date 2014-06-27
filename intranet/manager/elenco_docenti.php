<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

if(!isset($_GET['offset']))
    $offset = 0;
else
    $offset = $_GET['offset'];

$limit = 15;
$l_ext = $offset + 1;
$r_ext = $limit + $offset;

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

if(!isset($_GET['second'])){
	$res_docenti = $db->execute($sel_docenti);
	//print $sel_links;
	$count = $res_docenti->num_rows;
	$_SESSION['count_teac'] = $count;
}
else{
	$sel_docenti .= " LIMIT $limit OFFSET $offset";
	$res_docenti = $db->execute($sel_docenti);
}

if($offset == 0)
	$page = 1;
else
	$page = ($offset / $limit) + 1;

$pagine = ceil($_SESSION['count_teac'] / $limit);
if($pagine < 1)
	$pagine = 1;

if($r_ext > $_SESSION['count_teac']){
	$r_ext = $_SESSION['count_teac'];
}

// dati per la paginazione (navigate.php)
$colspan = 4;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_teac";
$row_class = "docs_row";
$row_class_menu = " docs_row_menu";
$nav_params = "&order=$order";

include "elenco_docenti.html.php";

?>