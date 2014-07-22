<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

if(!isset($_GET['step'])){
	$step = 1;
}
else{
	$step = $_GET['step'];
}
$_SESSION['wiz_step'] = $step;

$year = $_SESSION['__current_year__'];
$anno = $year->get_ID();

$sel_cdc = "SELECT COUNT(*) FROM rb_cdc WHERE id_anno = $anno";
$exist_cdc = $db->executeCount($sel_cdc);

$sel_reg = "SELECT COUNT(*) FROM rb_reg_classi WHERE id_anno = $anno";
$exist_reg = $db->executeCount($sel_reg);

$sel_sch = "SELECT COUNT(*) FROM rb_orario WHERE anno = $anno";
$exist_sch = $db->executeCount($sel_sch);

$check_data1 = "SELECT COUNT(*) FROM rb_scrutini WHERE anno = $anno AND quadrimestre = 1";
$count_data1 = $db->executeCount($check_data1);

$check_data2 = "SELECT COUNT(*) FROM rb_scrutini WHERE anno = $anno AND quadrimestre = 2";
$count_data2 = $db->executeCount($check_data2);

include_once "wiz_first_install.html.php";
