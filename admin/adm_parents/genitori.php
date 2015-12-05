<?php

require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$offlink = "";

$school_order = 0;
$classes_table = "rb_classi";
$params = null;
if(isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$_SESSION['school_order'] = $_GET['school_order'];
	$school_order = $_GET['school_order'];
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
    $params = "AND {$classes_table}.ordine_di_scuola = ".$school_order;
}
else if($_SESSION['__school_order__'] != 0){
	$school_order = $_SESSION['__school_order__'];
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
    $params = "AND {$classes_table}.ordine_di_scuola = ".$school_order;
}
else{
	$_SESSION['school_order'] = 0;
}

// indica se attivati i filtri
$filtered = false;
$query = "";

$parents = array();
$uids = array();
//if(!isset($_REQUEST['sel'])) {
	$sel_user = "SELECT rb_utenti.uid, rb_utenti.username, CONCAT_WS(' ', rb_utenti.cognome, rb_utenti.nome) AS nome, CONCAT_WS(' ', rb_alunni.cognome, rb_alunni.nome) AS al_name, CONCAT({$classes_table}.anno_corso, {$classes_table}.sezione) AS desc_classe, codice, rb_sedi.nome AS sede FROM rb_utenti, rb_alunni, rb_genitori_figli, {$classes_table}, rb_gruppi_utente, rb_sedi, rb_tipologia_scuola WHERE {$classes_table}.id_classe = rb_alunni.id_classe AND sede = id_sede AND {$classes_table}.ordine_di_scuola = id_tipo AND rb_utenti.uid = rb_gruppi_utente.uid AND gid = 4 ".$params." AND rb_alunni.id_alunno = rb_genitori_figli.id_alunno AND rb_genitori_figli.id_genitore = rb_utenti.uid AND rb_alunni.attivo = 1 ";
	try{
		$res_user = $db->executeQuery($sel_user);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	while($p = $res_user->fetch_assoc()) {
		$parents[] = $p;
		if (!in_array($p['uid'], $uids)) {
			$uids[] = $p['uid'];
		}
	}
//}

/*
 * ordinamento dati
 */
$order = array("nome");
if(isset($_REQUEST['order'])) {
	array_unshift($order, $_REQUEST['order']);
};
$msarray = new ArrayMultiSort($parents);
$msarray->setSortFields($order);
$msarray->sort();
$ordered_parents = $msarray->getData();

/*
if(isset($_REQUEST['classe'])){
	$filtered = true;
	$query .= "&classe=".$_REQUEST['classe'];
	$sel_user .= " AND rb_alunni.id_classe = ".$_REQUEST['classe']." ";
}
if(isset($_REQUEST['nome']) && (trim($_REQUEST['nome']) != "")){
	$filtered = true;
	$query .= "&nome=".$_REQUEST['nome'];
	$sel_user .= " AND (rb_utenti.nome LIKE '%".strtoupper($_REQUEST['nome'])."%' OR rb_utenti.cognome LIKE '%".strtoupper($_REQUEST['nome'])."%') ";
}
if(isset($_REQUEST['aname']) && (trim($_REQUEST['aname']) != "")){
	$filtered = true;
	$query .= "&aname=".$_REQUEST['aname'];
	$sel_user .= " AND (rb_alunni.nome LIKE '%".strtoupper($_REQUEST['aname'])."%' OR rb_alunni.cognome LIKE '%".strtoupper($_REQUEST['aname'])."%') ";
}

if(isset($_REQUEST['order']) && ($_REQUEST['order'] == "class")){
	$sel_user .= "ORDER BY sezione, anno_corso, cognome, nome";
	$new_order = "nome";
	$current_order = "class";
	$button_label = "Ordina per nome";
}
else{
	//$sel_user .= "ORDER BY cognome, nome, classe";
	$new_order = "class";
	$current_order = "nome";
	$button_label = "Ordina per classe";
}
$query .= " ORDER BY rb_utenti.cognome, rb_utenti.nome, rb_alunni.cognome, rb_alunni.nome ";

if(!isset($_GET['second'])){
	
    //print $sel_links;
    $count = $res_user->num_rows;
    $_SESSION['count_genitori'] = $count;
}
else{
    $sel_user .= " LIMIT $limit OFFSET $offset";
	try{
    	$res_user = $db->executeQuery($sel_user);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
}
*/


$_SESSION['count_parents'] = count($uids);

// dati per la paginazione (navigate.php)
$colspan = 3;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_parents";
$row_class = "admin_void";
$expand = false;
$nav_params = "";
if(isset($_REQUEST['order'])) {
	$nav_params .= "&order=".$_REQUEST['order'];
}
if(isset($_GET['school_order'])){
	$nav_params .= "&school_order=".$_GET['school_order'];
}

$navigation_label = "gestione utenti";
$drawer_label = "Elenco genitori: estratti ".$_SESSION['count_parents'];

include "genitori.html.php";
