<?php

require_once "../../lib/start.php";

check_session(FAKE_WINDOW);
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = 0;

$offset = 0;
if (isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}

if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
	$sel_sede = "SELECT * FROM rb_sedi WHERE id_sede = ".$_REQUEST['id'];
	$res_sede = $db->executeQuery($sel_sede);
	$sede = $res_sede->fetch_assoc();
	$_i = $_REQUEST['id'];
}
else{
	$my_date = date("d/m/Y");
	$_i = 0;
}

$navigation_label = "Area amministrazione: gestione sedi";

include "dettaglio_sede.html.php";