<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$teacher = "";
$label = "personali";
$link = "elenco_compiti.php";

if(!isset($_REQUEST['all'])){
	$teacher = "AND rb_impegni.docente = ".$_SESSION['__user__']->getUid();
	$label = "tutti";
	$link = "elenco_compiti.php?all=1";
}

$sel_act = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio >= NOW() AND rb_impegni.tipo = 2 $teacher ORDER BY data_inizio DESC";
$res_act = $db->execute($sel_act);
//print $sel_act;

if($res_act->num_rows > 0){
	$sel_dates = "SELECT DISTINCT(data_inizio) FROM rb_impegni WHERE classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio >= NOW() AND rb_impegni.tipo = 2 $teacher ORDER BY data_inizio DESC";
	$res_dates = $db->execute($sel_dates);
}
$navigation_label = "gestione classe";
$drawer_label = "Compiti assegnati";
if(!isset($_REQUEST['all'])){
	$drawer_label .= " (personali)";
}
else {
	$drawer_label .= " (tutti)";
}

include "elenco_compiti.html.php";
