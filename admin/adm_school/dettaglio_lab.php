<?php

require_once "../../lib/start.php";

check_session(FAKE_WINDOW);
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = 0;

if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
	$sel_lab = "SELECT * FROM rb_aule_speciali WHERE id_lab = ".$_REQUEST['id'];
	$res_lab = $db->executeQuery($sel_lab);
	$lab = $res_lab->fetch_assoc();
	$_i = $_REQUEST['id'];
}
else{
	$my_date = date("d/m/Y");
	$_i = 0;
}

$sel_sedi = "SELECT * FROM rb_sedi ORDER BY id_sede";
$res_sedi = $db->executeQuery($sel_sedi);

$navigation_label = "gestione scuola";
$drawer_label = "Dettaglio aula speciale";

include "dettaglio_lab.html.php";
