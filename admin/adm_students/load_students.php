<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = getAdminLevel($_SESSION['__user__']);

$school_order = 0;
$params = "";
if($_SESSION['__school_order__'] != 0){
	$school_order = $_SESSION['__school_order__'];
}
else if(isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$_SESSION['school_order'] = $_GET['school_order'];
	$school_order = $_GET['school_order'];
}
$log_file = "{$school_order}account_studenti".date("Ymd").".txt";

if($school_order != 0){
	$params = "AND id_tipo = {$school_order}";
}
$sel_tipologie = "SELECT * FROM rb_tipologia_scuola WHERE id_tipo != 999 $params";
$res_tipologie = $db->execute($sel_tipologie);

$navigation_label = "nuovo anno";
$drawer_label = "Importazione alunni";

include "load_students.html.php";
