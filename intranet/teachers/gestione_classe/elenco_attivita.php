<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

require_once "../reload_class_in_session.php";

$teacher = "";
$label = "personali";
$link = "elenco_attivita.php";

if(!isset($_REQUEST['all'])){
	$teacher = "AND rb_impegni.docente = ".$_SESSION['__user__']->getUid();
	$label = "tutte";
	$link = "elenco_attivita.php?all=1";
}

$sel_act = "SELECT rb_impegni.*, rb_materie.materia AS mat 
			FROM rb_impegni LEFT JOIN rb_materie ON rb_materie.id_materia = rb_impegni.materia 
			WHERE classe = ".$_SESSION['__classe__']->get_ID()." 
			AND anno = ".$_SESSION['__current_year__']->get_ID()." 
			AND DATE(data_fine) >= DATE(NOW()) 
			AND rb_impegni.tipo = 1 $teacher 
			ORDER BY data_inizio ASC";
$res_act = $db->execute($sel_act);

$navigation_label = "gestione classe";
$drawer_label = "Attivit&agrave; programmate";
if(!isset($_REQUEST['all'])){
	$drawer_label .= " (personali)";
}
else {
	$drawer_label .= " (tutte)";
}

setlocale(LC_ALL, "it_IT.utf8");

include "elenco_attivita.html.php";
