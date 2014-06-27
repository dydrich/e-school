<?php

/*
 * inserimento e modifica del coordinatore e del segretario di classe
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$classID = $_GET['id'];

$anno = $_SESSION['__current_year__']->get_ID();

$sel_seg = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti, rb_cdc WHERE uid = rb_docenti.id_docente AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_classe = $classID AND rb_cdc.id_anno = $anno GROUP BY uid, nome, cognome, coordinatore ORDER BY cognome, nome";
try{
	$res_seg = $db->executeQuery($sel_seg);
} catch (MySQLException $ex){
		$ex->alert();
}

$sel_coord = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti, rb_classi WHERE uid = rb_docenti.id_docente AND tipologia_scuola = ordine_di_scuola AND id_classe = {$classID} ORDER BY cognome, nome";
try{
	$res_coord = $db->executeQuery($sel_coord);
} catch (MySQLException $ex){
	$ex->alert();
}

$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE id_classe = {$classID} AND sede = id_sede";
try{
	$res_classe = $db->executeQuery($sel_classe);
} catch (MySQLException $ex){
	$ex->alert();
}
$classe = $res_classe->fetch_assoc();

include "coord.html.php";