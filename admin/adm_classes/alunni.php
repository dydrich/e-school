<?php

/*
 * elenco degli alunni per classe
 * permette di cambiare la classe dell'alunno: tale operazione aggiorna i dati nel registro elettronico
 */

require "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = getAdminLevel($_SESSION['__user__']);

$offset = 0;
if(isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}

$sel_alunni = "SELECT id_alunno, cognome, nome FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND rb_alunni.id_classe = ".$_REQUEST['id_classe']." ORDER BY cognome, nome";
$res_alunni = $db->execute($sel_alunni);

$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE id_classe = {$_REQUEST['id_classe']} AND sede = id_sede";
try{
	$res_classe = $db->executeQuery($sel_classe);
} catch (MySQLException $ex){
	$ex->alert();
}
$myclass = $res_classe->fetch_assoc();
$classes_table = "rb_vclassi_s{$myclass['ordine_di_scuola']}";

$sel_classi = "SELECT CONCAT_WS(' ', anno_corso, sezione) AS classe, id_classe, {$classes_table}.ordine_di_scuola, nome FROM {$classes_table}, rb_sedi WHERE sede = id_sede ORDER BY sezione, classe";
$res_classi = $db->executeQuery($sel_classi);

$navigation_label = "gestione classi";
$drawer_label = "Elenco alunni classe ". $myclass['anno_corso'].$myclass['sezione']." - ". $myclass['nome']." (<span id='st_count'>". $res_alunni->num_rows ."</span>)";

include "alunni.html.php";
