<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$sel_anni = "SELECT * FROM rb_anni WHERE id_anno <= ".$_SESSION['__current_year__']->get_ID()." ORDER BY id_anno DESC";
try{
	$res_anni = $db->executeQuery($sel_anni);
} catch (MySQLException $ex){
    $ex->alert();
	exit;
}

if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
    // modifica
    $sel_prj = "SELECT * FROM rb_progetti WHERE id_progetto = ".$_REQUEST['id'];
    try{
    	$res_prj = $db->executeQuery($sel_prj);
    } catch (MySQLException $ex){
    	$ex->alert();
		exit;
	}
    $project = $res_prj->fetch_assoc();
	
    $sel_teachers = "SELECT rb_utenti.nome, rb_utenti.cognome FROM rb_utenti, rb_responsabili_progetto WHERE rb_utenti.uid = docente AND progetto = ".$_REQUEST['id']." ORDER BY cognome, nome";
    try{
    	$res_teachers = $db->executeQuery($sel_teachers);
    } catch (MySQLException $ex){
    	$ex->alert();
		exit;
	}
    $teachers_string = array();
    while($ins = $res_teachers->fetch_assoc()){
    	array_push($teachers_string, $ins['cognome']." ".$ins['nome']);
    }
    // documenti in archivio per il progetto
    $sel_docs = "SELECT COUNT(*) FROM rb_documents WHERE progetto = ".$_REQUEST['id'];
    try{
    	$count_docs = $db->executeCount($sel_docs);
    } catch (MySQLException $ex){
    	$ex->alert();
		exit;
	}
    $_i = $_REQUEST['id'];
}
else{
    // nuovo progetto
    $_i = $count_docs = 0;
}

include "dettaglio_progetto.html.php";
