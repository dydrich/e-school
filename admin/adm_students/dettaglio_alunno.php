<?php

require_once "../../lib/start.php";

check_session(FAKE_WINDOW);
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

$back_link = "alunni.php";
$offset = 0;
if (isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}
if($offset != 0){
	$back_link .= "?second=1&offset={$offset}";
}
$type = $_REQUEST['type'];

$classes_table = "rb_classi";
if($_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
}
else if($_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
}

$sel_classi = "SELECT {$classes_table}.*, codice, nome FROM {$classes_table}, rb_tipologia_scuola, rb_sedi WHERE sede = id_sede AND {$classes_table}.ordine_di_scuola = id_tipo ORDER BY sezione, anno_corso";
$res_classi =$db->execute($sel_classi);

if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
    // modifica
    $sel_usr = "SELECT * FROM rb_alunni WHERE id_alunno = ".$_REQUEST['id'];
    try{
    	$res_usr = $db->execute($sel_usr);
    } catch (MySQLException $ex){
    	$ex->alert();
    }
    
    $alunno = $res_usr->fetch_assoc();
    $_i = $_REQUEST['id'];
    $old_class= $alunno['id_classe'];
	$drawer_label = "Dettaglio alunno";
}
else{
    // nuovo utente
	$drawer_label = "Nuovo alunno";
    $_i = $old_class = 0;
}

$navigation_label = "gestione utenti";
if (isset($_SESSION['area_from'])) $navigation_label = setNavigationLabel($_SESSION['__school_order__']);

include "dettaglio_alunno.html.php";
