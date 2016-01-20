<?php

require_once "../../lib/start.php";
require_once "../../lib/Classroom.php";

check_session(FAKE_WINDOW);
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = 0;

if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
	$lab = new \eschool\Classroom($_REQUEST['id'], new MySQLDataLoader($db));
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
