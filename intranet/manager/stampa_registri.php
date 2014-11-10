<?php

require_once "../../lib/start.php";
require_once "../../lib/RBTime.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$classes_table = "rb_classi";
if ($_SESSION['__school_order__']){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];
}

$sel_classi = "SELECT * FROM {$classes_table} ORDER BY sezione, anno_corso";
$res_classi = $db->execute($sel_classi);

/*
 * per il download del registro
*/
$_SESSION['no_file'] = array("referer" => "intranet/manager/stampa_registri.php", "path" => "intranet/manager/", "relative" => "stampa_registri.php");

$drawer_label = "Registri di classe";

include "stampa_registri.html.php";
