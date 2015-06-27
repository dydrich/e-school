<?php

require_once "../../lib/start.php";
require_once "../../lib/PageMenu.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

/*
 * accesso da segreteria
 */
$menu = "../adm_users/menu.php";
if (isset($_SESSION['area_from'])) {
	$menu = $_SESSION['area_from']['menu'];
}

if(!isset($_REQUEST['offset'])){
    $offset = 0;
}
else{
    $offset = $_REQUEST['offset'];
}

$limit = 12;

$classes_table = "rb_classi";
$school_order = 0;
if(isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$_SESSION['school_order'] = $_GET['school_order'];
	$school_order = $_GET['school_order'];
}
else if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school_order = $_SESSION['__school_order__'];
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
	$school_order = $_SESSION['school_order'];
}
else{
	$_SESSION['school_order'] = 0;
}

/*
 * filtri
 */
$classi = array("1" => "Prima", "2" => "Seconda", "3" => "Terza");
if($school_order == 2 || $school_order == 0){
	$classi['4'] = "Quarta";
	$classi['5'] = "Quinta";
}

$sel_sezioni = "SELECT DISTINCT(sezione) FROM {$classes_table} ORDER BY sezione";
$res_sezioni = $db->executeQuery($sel_sezioni);

// indica se attivati i filtri
$filtered = false;
$query = "";
$query_label = "";
if (isset($_REQUEST['order'])){
	$nav_params = "&order=".$db->real_escape_string($_REQUEST['order']);
}

$sel_user = "SELECT id_alunno, rb_alunni.nome, cognome, username, password, rb_alunni.id_classe, CONCAT(anno_corso,sezione) AS classe, codice, rb_sedi.nome AS sede FROM rb_alunni, {$classes_table}, rb_tipologia_scuola, rb_sedi WHERE rb_alunni.id_classe = {$classes_table}.id_classe AND id_tipo = {$classes_table}.ordine_di_scuola AND sede = id_sede AND rb_alunni.attivo = '1' ";
if(isset($_REQUEST['sezione']) && isset($_REQUEST['classe'])){
	$filtered = true;
	$query .= "&classe=".$_REQUEST['classe']."&sezione=".$_REQUEST['sezione'];
	$nav_params .= "&classe=".$_REQUEST['classe']."&sezione=".$_REQUEST['sezione'];
	$sel_user .= " AND anno_corso = '".$_REQUEST['classe']."' AND sezione = '".$_REQUEST['sezione']."' ";
	$query_label .= "Classe ".$_REQUEST['classe'].$_REQUEST['sezione'];
}
else if(isset($_REQUEST['sezione'])){
	$filtered = true;
	$query .= "&sezione=".$_REQUEST['sezione'];
	$nav_params .= "&sezione=".$_REQUEST['sezione'];
	$sel_user .= " AND sezione = '".$_REQUEST['sezione']."' ";
	$query_label .= "Sezione ".$_REQUEST['sezione'];
}
else if(isset($_REQUEST['classe'])){
	$filtered = true;
	$query .= "&classe=".$_REQUEST['classe'];
	$nav_params .= "&classe=".$_REQUEST['classe'];
	$sel_user .= " AND anno_corso = '".$_REQUEST['classe']."' ";
	switch($_REQUEST['classe']){
		case 1:
			$query_label = "Classi prime";
			break;
		case 2:
			$query_label = "Classi seconde";
			break;
		case 3:
			$query_label = "Classi terze";
			break;
		case 4:
			$query_label = "Classi quarte";
			break;
		case 5:
			$query_label = "Classi quinte";
			break;
	}
}
if(isset($_REQUEST['anno'])){
	$filtered = true;
	$query .= "&anno=".$_REQUEST['anno'];
	$nav_params .= "&anno=".$_REQUEST['anno'];
	$from = $_REQUEST['anno']."-01-01";
	$to = $_REQUEST['anno']."-12-31";
	$sel_user .= " AND data_nascita BETWEEN '$from' AND '$to' ";
	if($query_label == "")
		$query_label = "Anno di nascita ".$_REQUEST['anno'];
	else 
		$query_label .= " - Anno di nascita ".$_REQUEST['anno'];
}
if(isset($_REQUEST['nome']) && (trim($_REQUEST['nome']) != "")){
	$filtered = true;
	$query .= "&nome=".$_REQUEST['nome'];
	$nav_params .= "&nome=".$_REQUEST['nome'];
	$sel_user .= " AND (rb_alunni.nome LIKE '%".strtoupper($_REQUEST['nome'])."%' OR cognome LIKE '%".strtoupper($_REQUEST['nome'])."%') ";
	if($query_label == "")
		$query_label = "Ricerca per nome e/o cognome: *".$_REQUEST['nome']."*";
	else 
		$query_label .= " - Ricerca per nome e/o cognome: *".$_REQUEST['nome']."*";
}

if($school_order != 0){
	$sel_user .= " AND id_tipo = {$school_order} ";
}

if(isset($_REQUEST['order']) && ($_REQUEST['order'] == "desc_classe")){
	$sel_user .= "ORDER BY sezione, anno_corso, cognome, rb_alunni.nome";
	$new_order = "nome";
	$current_order = "class";
	$button_label = "Ordina per nome";
	$icon = "fa-sort-alpha-asc";
}
else if(isset($_REQUEST['order']) && ($_REQUEST['order'] == "tipologia")){
	$sel_user .= "ORDER BY id_tipo, sezione, anno_corso, cognome, rb_alunni.nome";
	$new_order = "nome";
	$current_order = "tipologia";
	$button_label = "Ordina per nome";
	$icon = "fa-sort-alpha-asc";
}
else{
	$sel_user .= "ORDER BY cognome, rb_alunni.nome, sezione, anno_corso";
	$new_order = "desc_classe";
	$current_order = "nome";
	$button_label = "Ordina per classe";
	$icon = "fa-sort-numeric-asc";
}

if(!isset($_GET['second'])){
    $res_user = $db->execute($sel_user);
    //print $sel_links;
    $count = $res_user->num_rows;
    $_SESSION['count_alunni'] = $count;
}
else{
    $sel_user .= " LIMIT $limit OFFSET $offset";
    $res_user = $db->execute($sel_user);
}

if($query_label != "")
	$query_label = "[".$query_label."]";

if($offset == 0)
    $page = 1;
else
    $page = ($offset / $limit) + 1;

$pagine = ceil($_SESSION['count_alunni'] / $limit);
if($pagine < 1)
    $pagine = 1;
    
// dati per la paginazione (navigate.php)
$colspan = 3;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_alunni";
$row_class = "admin_void";

$navigation_label = "gestione utenti";
if (isset($_SESSION['area_from'])) $navigation_label = setNavigationLabel($_SESSION['__school_order__']);
$drawer_label = "Elenco alunni: pagina $page di $pagine (estratti ".$_SESSION['count_alunni']." alunni) "; //<span style='text-decoration: underline'>$query_label</span>";

include "alunni.html.php";
