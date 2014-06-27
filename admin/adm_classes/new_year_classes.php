<?php

/*
 * front page della procedura di attivazione classi per il nuovo anno
 * si compone dei seguenti step:
 * 1. ripetenti.php (scelta dei ripetenti)
 * 2. cancella_terze.php (cancellazione delle classi terze uscenti)
 * 3-4. avanza_classi.php (avanzamento delle classi al nuovo anno: 2->3 e 1->2)
 * 5. creazione nuove classi prime
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = 0;

$sel_classi = "SELECT * FROM rb_classi ORDER BY sezione, anno_corso";
try{
	$res_classi = $db->executeQuery($sel_classi);
} catch (MySQLException $ex){
	$ex->redirect();
}

/*
* recupero lo stato di avanzamento dell'operazione
*/
if(!isset($_SESSION['__new_classes_step__'])){
	$sel_step = "SELECT valore FROM rb_config WHERE variabile = 'stato_avanzamento_nuove_classi'";
	$_SESSION['__new_classes_step__'] = $db->executeCount($sel_step);
}

$navigation_label = "Area amministrazione: gestione classi";

include "new_year_classes.html.php";