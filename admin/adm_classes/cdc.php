<?php

/*
 * inserimento e modifica dei docenti del cdc
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$classID = $_GET['id'];
$anno = $_SESSION['__current_year__']->get_ID();

$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE id_classe = {$classID} AND sede = id_sede";
try{
	$res_classe = $db->executeQuery($sel_classe);
} catch (MySQLException $ex){
	$ex->alert();
}
$classe = $res_classe->fetch_assoc();

$sel_mat = "SELECT rb_materie.id_materia, materia, idpadre FROM rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_classe = $classID AND id_anno = {$anno} ORDER BY id_materia";
try{
	$res_mat = $db->executeQuery($sel_mat);
} catch (MySQLException $ex){
	$ex->alert();
}

$sel_cdc = "SELECT id_docente, id_materia FROM rb_cdc WHERE id_classe = $classID AND id_anno = $anno ";
try{
	$res_cdc = $db->executeQuery($sel_cdc);
} catch (MySQLException $ex){
		$ex->alert();
}	
$consiglio = array();
while($con = $res_cdc->fetch_assoc()){
    $consiglio[$con['id_materia']] = $con['id_docente'];
}

// sostegno
$sel_sost = "SELECT uid, nome, cognome, ore FROM rb_utenti, rb_assegnazione_sostegno WHERE anno = {$anno} AND classe = {$classID} AND docente = uid";
try{
	$res_sost = $db->execute($sel_sost);
} catch (MySQLException $ex){
	$ex->redirect();
}

// elenco docenti per sostegno
$sostegno = 27;
if ($_SESSION['school_order'] == 2) {
	$sostegno = 41;
}
$sel_teac = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti WHERE id_docente = uid AND materia = ".$sostegno."  ORDER BY cognome, nome";
$res_teac = $db->execute($sel_teac);

$navigation_label = "gestione classi";
$drawer_label = "Consiglio di classe: ".$classe['anno_corso'].$classe['sezione']." - ". $classe['nome'];

include "cdc.html.php";
