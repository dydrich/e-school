<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$admin_level = 0;

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$sel_sedi = "SELECT rb_sedi.*, CONCAT_WS(' ', rb_utenti.cognome, rb_utenti.nome) AS responsabile FROM rb_sedi LEFT JOIN rb_utenti ON responsabile = uid ";
try{
    $res_sedi = $db->executeQuery($sel_sedi);
} catch (MySQLException $ex){
    $ex->redirect();
}
//print $sel_links;
$count = $res_sedi->num_rows;
$_SESSION['count_sedi'] = $count;

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
$drawer_label = "Elenco sedi";

include "sedi.html.php";
