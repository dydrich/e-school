<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";
require_once "../../lib/ReportManager.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";
$page = "pagella.php";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$classe = $_SESSION['__classe__']->get_ID();
$school_order = $_SESSION['__classe__']->getSchoolOrder();
$pagelle = array();
$pagelle_old = array();

// pagelle anno in corso
$sel_pagelle1 = "SELECT rb_pagelle.*, anno, descrizione, quadrimestre, data_pubblicazione, ora_pubblicazione FROM rb_pagelle, rb_pubblicazione_pagelle, rb_anni WHERE rb_pubblicazione_pagelle.id_pagella = id_pubblicazione AND id_anno = anno AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_alunno = {$_SESSION['__current_son__']} AND quadrimestre = 1 ORDER BY id_pagella ";
$sel_pagelle2 = "SELECT rb_pagelle.*, anno, descrizione, quadrimestre, data_pubblicazione, ora_pubblicazione FROM rb_pagelle, rb_pubblicazione_pagelle, rb_anni WHERE rb_pubblicazione_pagelle.id_pagella = id_pubblicazione AND id_anno = anno AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_alunno = {$_SESSION['__current_son__']} AND quadrimestre = 2 ORDER BY id_pagella ";
try {
	$res_pagelle1 = $db->executeQuery($sel_pagelle1);
	$res_pagelle2 = $db->executeQuery($sel_pagelle2);
} catch (MYSQLException $ex) {
	$ex->redirect();
	exit;
}
$p1 = $res_pagelle1->fetch_assoc();
$pagelle[0] = $p1;
$p2 = $res_pagelle2->fetch_assoc();
$pagelle[1] = $p2;

// pagelle anni precedenti
$sel_old_pagelle = "SELECT rb_pagelle.*, anno, descrizione, quadrimestre, data_pubblicazione, ora_pubblicazione FROM rb_pagelle, rb_pubblicazione_pagelle, rb_anni WHERE rb_pubblicazione_pagelle.id_pagella = id_pubblicazione AND id_anno = anno AND id_anno < ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = 2 AND id_alunno = {$_SESSION['__current_son__']} ORDER BY anno DESC";
try {
	$res_old_pagelle = $db->executeQuery($sel_old_pagelle);
} catch (MYSQLException $ex) {
	$ex->redirect();
	exit;
}
while($p = $res_old_pagelle->fetch_assoc()){
	$pagelle_old[] = $p;
}

$navigation_label = "Registro elettronico genitori: alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$_SESSION['no_file'] = array("referer" => "intranet/genitori/pagella.php", "path" => "intranet/genitori/", "relative" => "pagella.php");

include "pagella.html.php";