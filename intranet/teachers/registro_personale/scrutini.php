<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";
require_once "../../../lib/RBUtilities.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

require_once "../reload_class_in_session.php";

if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getSubject() == 27)){
	header("Location: scrutini_classe.php");
}

if ($_SESSION['__user__']->isSupplyTeacher()) {
	$last_day = $db->executeCount("SELECT MAX(data_fine_supplenza) FROM rb_supplenze, rb_classi_supplenza WHERE rb_supplenze.id_supplenza = rb_classi_supplenza.id_supplenza AND classe = {$_SESSION['__classe__']->get_ID()}");
	if ($last_day < date("Y-m-d")) {
		$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
		header("Location: {$_SESSION['__path_to_reg_home__']}no_permission.php");
	}
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$date_max = date("Y-m-d", strtotime($fine_q." +35 days"));
	if(date("Y-m-d") > $date_max){
		$q = 2;
	}
	else{
		$q = 1;
	}
}
if($q == 1)
	$label = " primo quadrimestre";
else
	$label = " finali";

/*
 * verifica se scrutini ancora aperti, per modifica
 */
$sel_scr_op = "SELECT stato_scrutinio, quadrimestre FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY quadrimestre DESC";
$res_scr_op = $db->execute($sel_scr_op);
$scr_1q = $scr_2q = 0;
while ($row = $res_scr_op->fetch_assoc()){
	if ($row['quadrimestre'] == 1){
		$scr_1q = $row['stato_scrutinio'];
	}
	else {
		$scr_2q = $row['stato_scrutinio'];
	}
}

$readonly = false;
if (($q == 1 && $scr_1q == 1) || ($q == 2 && $scr_2q == 1)){
	$readonly = true;
}

$mat = $_SESSION['__user__']->getSubject();

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

if(count($_SESSION['__subjects__']) > 0) {
	$k = 0;
	foreach ($_SESSION['__subjects__'] as $mt) {
		//print "while";
		if (isset($_REQUEST['subject'])) {
			if ($_REQUEST['subject'] == $mt['id']) {
				$idm = $mt['id'];
				$_mat = $mt['mat'];
			}
		}
		else {
			if (isset($_SESSION['__materia__'])) {
				if ($_SESSION['__materia__'] == $mt['id']) {
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
				else {
					if ($k == 0) {
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
				}
			}
			else {
				if ($k == 0) {
					//print "k==0";
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
			}
		}
		$k++;
	}
	$_SESSION['__materia__'] = $idm;
}

if(isset($_REQUEST['subject']))
	$_SESSION['__materia__'] = $_REQUEST['subject']; 

$sel_dati = "SELECT rb_alunni.cognome, rb_alunni.nome, alunno, voto, assenze FROM rb_alunni LEFT JOIN rb_scrutini ON id_alunno = alunno WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND id_classe = ". $_SESSION['__classe__']->get_ID() ." AND classe = id_classe AND quadrimestre = $q AND materia = ".$_SESSION['__materia__']." ORDER BY cognome, nome";
$res_dati = $db->execute($sel_dati);

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Scrutini".$label;

include "scrutini.html.php";
