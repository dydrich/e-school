<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$admin_level = 0;

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$sel_labs = "SELECT rb_aule_speciali.*, rb_sedi.nome AS sede FROM rb_sedi, rb_aule_speciali WHERE rb_sedi.id_sede = rb_aule_speciali.sede ";
try{
	$res_labs = $db->executeQuery($sel_labs);
} catch (MySQLException $ex){
	$ex->redirect();
}
$count = $res_labs->num_rows;
$_SESSION['count_labs'] = $count;


$navigation_label = "gestione scuola";
$drawer_label = "Elenco laboratori (estratti ".$count." elementi)";

include "labs.html.php";
