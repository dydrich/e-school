<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$admin_level = 0;

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

if(!isset($_REQUEST['offset']))
	$offset = 0;
else
	$offset = $_REQUEST['offset'];

$limit = 12;

$sel_sedi = "SELECT rb_sedi.* FROM rb_sedi ";

if(!isset($_GET['second'])){
	try{
		$res_sedi = $db->executeQuery($sel_sedi);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	//print $sel_links;
	$count = $res_sedi->num_rows;
	$_SESSION['count_sedi'] = $count;
}
else{
	$sel_sedi .= "LIMIT $limit OFFSET $offset";
	$res_sedi = $db->execute($sel_sedi);
}

if($offset == 0)
	$page = 1;
else
	$page = ($offset / $limit) + 1;

$pagine = ceil($_SESSION['count_sedi'] / $limit);
if($pagine < 1)
	$pagine = 1;

// dati per la paginazione (navigate.php)
$colspan = 2;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_sedi";
$row_class = "admin_void";
$expand = false;
$row_class_menu = "admin_nav";

/*
 * procedura guidata prima installazione
 * first install wizard
 */
$goback = "Torna al menu";
$goback_link = "../index.php";
if(isset($_SERVER['HTTP_REFERER']) && basename($_SERVER['HTTP_REFERER']) == "wiz_first_install.php"){
	$goback = "Torna al wizard";
	$goback_link = "../wiz_first_install.php";
}

$navigation_label = "gestione scuola";
$drawer_label = "Elenco sedi: pagina $page di $pagine";

include "sedi.html.php";
