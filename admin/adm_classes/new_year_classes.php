<?php

/*
 * front page della procedura di attivazione classi per il nuovo anno
 * si compone dei seguenti step:
 * 1. ripetenti.php (scelta dei ripetenti)
 * 2. cancella_classi_terminali.php (cancellazione delle classi terze uscenti)
 * 3-4. avanza_classi.php (avanzamento delle classi al nuovo anno: 2->3 e 1->2)
 * 5. creazione nuove classi prime
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|AMS_PERM|APS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = 0;

$school_order = $_REQUEST['school_order'];
if ($school_order == 1){
	$cl_label = "scuola secondaria ";
	$term_cls = "terze";
}
else {
	$cl_label = "scuola primaria";
	$term_cls = "quinte";
}

$sel_classi = "SELECT * FROM rb_classi WHERE ordine_di_scuola = {$school_order} ORDER BY sezione, anno_corso";
try{
	$res_classi = $db->executeQuery($sel_classi);
} catch (MySQLException $ex){
	$ex->redirect();
}

/*
* recupero lo stato di avanzamento dell'operazione
*/
$sel_step = "SELECT valore FROM rb_config WHERE variabile = 'stato_avanzamento_nuove_classi_{$school_order}'";
$_SESSION['__new_classes_step__'] = $db->executeCount($sel_step);

$navigation_label = "nuovo anno";
$drawer_label = "Gestione classi per nuovo anno scolastico: $cl_label ";

include "new_year_classes.html.php";
