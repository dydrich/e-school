<?php

/*
 * interfaccia di creazione delle classi prime per nuovo anno
 * step 5 della procedura di attivazione classi per nuovo anno
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$sel_sezioni = "SELECT DISTINCT(sezione) FROM rb_classi ORDER BY sezione";
try{
	$res_sezioni = $db->executeQuery($sel_sezioni);
} catch (MySQLException $ex){
	$ex->redirect();
}

$alpha = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

include "nuove_prime.html.php";