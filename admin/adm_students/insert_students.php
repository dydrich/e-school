<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$school_order = 0;
if($_SESSION['__school_order__'] != 0){
	$school_order = $_SESSION['__school_order__'];
}
else if($_GET['school_order'] != 0){
	$_SESSION['school_order'] = $_GET['school_order'];
	$school_order = $_GET['school_order'];
}
$log_file = "{$school_order}account_studenti".date("Ymd").".txt";

$sel_classes = "SELECT id_classe, anno_corso, sezione, nome FROM rb_classi, rb_sedi WHERE sede = id_sede AND rb_classi.ordine_di_scuola = {$school_order} ORDER BY sezione, anno_corso ";
$res_classes = $db->executeQuery($sel_classes);

$navigation_label = "nuovo anno";
$drawer_label = "Inserimento alunni";

include "insert_students.html.php";
