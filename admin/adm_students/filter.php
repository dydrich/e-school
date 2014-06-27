<?php

require_once "../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$classes_table = "rb_classi";
$school_order = 0;
if($_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school_order = $_SESSION['__school_order__'];
}
else if($_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
	$school_order = $_SESSION['school_order'];
}

$classi = array("1" => "Prima", "2" => "Seconda", "3" => "Terza");
if($school_order == 2 || $school_order == 0){
	$classi['4'] = "Quarta";
	$classi['5'] = "Quinta";
}

$sel_sezioni = "SELECT DISTINCT(sezione) FROM {$classes_table} ORDER BY sezione";
$res_sezioni = $db->executeQuery($sel_sezioni);

include "filter.html.php";