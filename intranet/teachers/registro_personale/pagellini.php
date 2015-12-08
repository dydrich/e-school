<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/11/15
 * Time: 11.26
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$num_subject = count($_SESSION['__subjects__']);
if($num_subject == 2){
	$first_column = 60;
	$other_column = 20;
}
else if($num_subject == 3){
	$first_column = 49;
	$other_column = 17;
}
else if($num_subject == 4){
	$first_column = 40;
	$other_column = 15;
}

$drawer_label = "Gestione pagellino";
$months = array("11" => "Novembre", "12" => "Dicembre", "1" => "Gennaio", "3" => "Marzo", "4" => "Aprile", "5" => "Maggio");
$sel_active = "SELECT * FROM rb_pagellini WHERE data_apertura <= NOW() AND data_chiusura >= NOW()";
$res_active = $db->executeQuery($sel_active);

$active_report = array();
if ($res_active) {
	$active_report = $res_active->fetch_assoc();
	$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE rb_alunni.id_classe = ".$_SESSION['__classe__']->get_ID()." AND attivo = '1' ORDER BY cognome, nome";
	try{
		$res_alunni = $db->executeQuery($sel_alunni);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	$mat = $_SESSION['__user__']->getSubject();
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid(true)." AND rb_cdc.id_classe = ". $_SESSION['__classe__']->get_ID() ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND pagella = 1 AND id_anno = ".$_SESSION['__current_year__']->get_ID();
//print $sel_materie;
	try{
		$res_materie = $db->executeQuery($sel_materie);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	$materie = array();
	$materie_senza_alternativa = array();
	while($mt = $res_materie->fetch_assoc()){
		$materie[] = array("id" => $mt['id_materia'], "mat" => $mt['materia']);
		$materie_senza_alternativa[] = array("id" => $mt['id_materia'], "mat" => $mt['materia']);
	}
	$orig_materie = array();
	/*
	 * controllo materia alternativa
	 */
	$alt_subject = 46;
	$sel_alt_sub = "SELECT COUNT(*) FROM rb_materia_alternativa WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND classe = ".$_SESSION['__classe__']->get_ID()." AND docente = ".$_SESSION['__user__']->getUid();
	$res_alt_sub = $db->executeCount($sel_alt_sub);
	if ($res_alt_sub > 0) {
		$materie[] = array("id" => $alt_subject, "mat" => "Materia alternativa");
	}
	$drawer_label .= " mese di ".$months[$active_report['mese']];
}

$navigation_label = "registro personale";
include "pagellini.html.php";
