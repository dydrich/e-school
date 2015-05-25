<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$show_account = false;
$offset = 0;
if (isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}

$school_order = 0;
$classes_table = "rb_classi";
if (isset($_GET['school_order'])){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
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
if (isset($_REQUEST['search'])) {
	$classes_table = "rb_classi";
}

$back_link = "genitori.php?school_order={$school_order}";
if($offset != 0){
	$back_link .= "&second=1&offset={$offset}";
}

$sel_classi = "SELECT * FROM {$classes_table} ORDER BY sezione, anno_corso";
$res_classi = $db->executeQuery($sel_classi);

if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
    // modifica
    $sel_usr = "SELECT rb_utenti.*, email FROM rb_utenti LEFT JOIN rb_profili ON uid = id WHERE uid = ".$_REQUEST['id'];
    $res_usr = $db->executeQuery($sel_usr);
    $genitore = $res_usr->fetch_assoc();
    // figli?
    $sel_figli = "SELECT rb_alunni.id_alunno, rb_alunni.nome, rb_alunni.cognome FROM rb_alunni, rb_utenti, rb_genitori_figli, {$classes_table} WHERE rb_alunni.id_classe = {$classes_table}.id_classe AND rb_alunni.id_alunno = rb_genitori_figli.id_alunno AND rb_genitori_figli.id_genitore = rb_utenti.uid  AND rb_utenti.uid = ".$_REQUEST['id']." ORDER BY rb_alunni.cognome, rb_alunni.nome";
    //print $sel_figli;
    $res_figli = $db->executeQuery($sel_figli);
    $figli = "";
    $id_figli = array();
    if($res_figli->num_rows > 0){
	    while($figlio = $res_figli->fetch_assoc()){
	    	$figli .= "<a href='#' onclick='del(".$figlio['id_alunno'].")' id='al".$figlio['id_alunno']."'>".$figlio['cognome']." ".$figlio['nome']."</a>, ";
	    	$id_figli[$figlio['id_alunno']] = $figlio['cognome']." ".$figlio['nome'];	    	
	    }
	    $figli = substr($figli, 0, (strlen($figli) - 2));
    } else {
    	$figli = "";
    }
    $_i = $_REQUEST['id'];
	$drawer_label = "Dettaglio genitore";
}
else{
    // nuovo utente
	$drawer_label = "Nuovo genitore";
    $_i = 0;
    $show_account = true;
}

if (isset($_GET['referer']) && $_GET['referer'] == "inactive") {
	$back_link = "genitori_inattivi.php";
}

$navigation_label = "gestione utenti";

include "dettaglio_genitore.html.php";
