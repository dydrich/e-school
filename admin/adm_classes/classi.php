<?php

/*
 * elenco delle classi attive, per:
 *   - modifica di cdc
 *   - spostamento di alunni
 *   - compilazione orario di classe
 */

require_once "../../lib/start.php";
require_once "../../lib/Widget.php";
require_once "../../lib/PageMenu.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = getAdminLevel($_SESSION['__user__']);

if(!isset($_GET['offset'])) {
    $offset = 0;
}
else {
    $offset = $_GET['offset'];
}
$limit = 10;

$classes_table = "rb_classi";
$venue_params = "";
$school_order = null;
if (isset($_GET['school_order'])){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$school_order = $_GET['school_order'];
	$_SESSION['__school_order__'] = $_GET['school_order'];
	$_SESSION['school_order'] = $_GET['school_order'];
}
else if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school_order = $_SESSION['__school_order__'];
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
	$school_order = $_SESSION['school_order'];
}

$anno = $_SESSION['__current_year__']->get_ID();
$params = "";
$sel_venues = "SELECT * FROM rb_sedi WHERE (ordine_di_scuola = 0 OR ordine_di_scuola = {$school_order}) ORDER BY id_sede";
$res_venues = $db->execute($sel_venues);
$venues = array();
while($venue = $res_venues->fetch_assoc()){
	$venues[$venue['id_sede']] = array("id" => $venue['id_sede'], "desc" => $venue['nome']);
}

if(isset($_REQUEST['venue']) && $_REQUEST['venue']){
	$params = "AND id_sede = ".$_REQUEST['venue'];
	$sede = $venues[$_REQUEST['venue']]['desc'];
}

$sel_ordini = "SELECT * FROM rb_tipologia_scuola WHERE has_admin = 1 AND attivo = 1 ORDER BY id_tipo";
$res_ordini = $db->execute($sel_ordini);
$ordini = array();
while($ord = $res_ordini->fetch_assoc()){
	$ordini[$ord['id_tipo']] = $ord;
}

$sel_cls = "SELECT id_classe, anno_corso, tempo_prolungato, musicale, sezione, {$classes_table}.ordine_di_scuola, rb_sedi.nome ";
$sel_cls .= "FROM {$classes_table}, rb_sedi, rb_tipologia_scuola ";
$sel_cls .= "WHERE anno_corso <> 0 AND sede = rb_sedi.id_sede AND {$classes_table}.ordine_di_scuola = id_tipo AND rb_tipologia_scuola.attivo = 1 $params ORDER BY sede, sezione, anno_corso ";

if(!isset($_GET['second'])){
    $res_cls = $db->execute($sel_cls);
    //print $sel_links;
    $count = $res_cls->num_rows;
    $_SESSION['count_cls'] = $count;
}
else{
    $sel_cls .= "LIMIT $limit OFFSET $offset";
    $res_cls = $db->execute($sel_cls);
}

if($offset == 0){
    $page = 1;
}
else{
    $page = ($offset / $limit) + 1;
}

$pagine = ceil($_SESSION['count_cls'] / $limit);
if($pagine < 1){
    $pagine = 1;
}
    
// dati per la paginazione (navigate.php)
$colspan = 4;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_cls";
$row_class = "admin_void";
$nav_params = "&school_order={$school_order}";

/*
 * procedura guidata prima installazione
 * first install wizard
*/
$goback = "Torna al menu";
$goback_link = "../index.php";
if(basename($_SERVER['HTTP_REFERER']) == "wiz_first_install.php?step=3"){
	$goback = "Torna al wizard";
	$goback_link = "../wiz_first_install.php?step=3";
}

$navigation_label = "gestione classi";
$drawer_label = "Elenco classi ".truncateString($ordini[$school_order]['tipo'], 57)." ";
if(isset($sede)) {
	$drawer_label .= " [".$sede."]";
}
$drawer_label .= ": pagina $page di $pagine ";

include "classi.html.php";
