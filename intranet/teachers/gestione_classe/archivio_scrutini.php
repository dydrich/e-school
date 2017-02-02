<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/06/15
 * Time: 11.59
 * archivio scrutini anni precedenti
 */
require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$navigation_label = "gestione classe";
$drawer_label = "Archivio scrutini";
$page = getFileName();

require_once "../reload_class_in_session.php";

$school = $_SESSION['__user__']->getSchoolOrder();
$_SESSION['__school_order__'] = $school;

$year = $_SESSION['__current_year__']->get_ID();

$sel_anni = "SELECT id_anno, descrizione FROM rb_anni WHERE id_anno < $year ORDER BY id_anno DESC";
try{
	$res_anni = $db->executeQuery($sel_anni);
} catch(MySQLException $ex){
	$ex->redirect();
}
$anni_corso_classe = array();
while ($row = $res_anni->fetch_assoc()) {
	$anni_corso_classe[] = $row;
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$anno_corso = $_SESSION['__classe__']->get_anno();
$sezione = $_SESSION['__classe__']->get_sezione();

$anno_sel = isset($_REQUEST['sel']) ? $_REQUEST['sel'] : 1;

if (isset($_REQUEST['y']) && $_REQUEST['y'] != "") {
	if (isset($_REQUEST['sel'])) {
		$index = abs($anno_sel - 2);
	}
	else {
		$index = 0;
	}
	$num_colonne = 1;
	$first_column_width = 23;
	$column_width = null;
	$available_space = 100 - $first_column_width;
	$anno = $_REQUEST['y'];
	$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE id_materia = rb_scrutini.materia AND classe = {$_SESSION['__classe__']->get_ID()} AND quadrimestre = 2 AND anno = {$anno} AND tipologia_scuola = 1 AND rb_materie.id_materia != 26 AND rb_materie.id_materia != 30 GROUP BY rb_materie.id_materia, rb_materie.materia ORDER BY rb_materie.posizione_pagella";
	$res_materie = $db->executeQuery($sel_materie);
	$num_materie = $res_materie->num_rows;
	$num_colonne += ($num_materie * 2);
	$column_width = intval($available_space / ($num_colonne - 1));
	$materie = array();
	$comp = array();
	while($materia = $res_materie->fetch_assoc()){
		if($materia['materia'] == "Scienze motorie"){
			$materia['materia'] = "Smotorie";
		}
		if($materia['id_materia'] == 2){
			$comp = $materia;
		}
		else{
			$materie[] = $materia;
		}
	}
	if($comp){
		$materie[] = $comp;
	}

	$subjects = array();
	foreach ($materie as $sub) {
		$subjects[] = $sub['id_materia'];
	}
	$subjects_param = implode(", ", $subjects);

	$alunni = array();
	$sel_alunni = "SELECT cognome, nome, id_alunno FROM rb_alunni WHERE id_classe = ". $_SESSION['__classe__']->get_ID() ." ORDER BY cognome, nome";
	try{
		$res_alunni = $db->executeQuery($sel_alunni);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	while ($row = $res_alunni->fetch_assoc()) {
		$alunni[$row['id_alunno']] = array("alunno" => $row['cognome']." ".$row['nome'], "voti" => array());
		foreach ($materie as $sub) {
			$alunni[$row['id_alunno']]['voti'][$sub['id_materia']] = array("1q" => 0, "2q" => 0);
		}
		$alunni[$row['id_alunno']]['voti']['media'] = array("1q" => 0, "2q" => 0);
	}
	$sel_voti = "SELECT alunno, materia, voto, quadrimestre FROM rb_scrutini WHERE classe = ". $_SESSION['__classe__']->get_ID() ." AND anno = $anno";
	$res_voti = $db->executeQuery($sel_voti);
	while ($grade = $res_voti->fetch_assoc()) {
		if ($grade['voto'] != "" && isset($alunni[$grade['alunno']])) {
			if ($grade['quadrimestre'] == 1) {
				$alunni[$grade['alunno']]['voti'][$grade['materia']]['1q'] = $grade['voto'];
				//$alunni[$grade['alunno']]['voti']['media']['1q'] += $grade['voto'];
			}
			else if ($grade['quadrimestre'] == 2) {
				$alunni[$grade['alunno']]['voti'][$grade['materia']]['2q'] = $grade['voto'];
				//$alunni[$grade['alunno']]['voti']['media']['2q'] += $grade['voto'];
			}
		}
	}
	include "archivio_scrutini.html.php";
}
else {
	$ac2 = $anno_corso - 1;
	$ac1 = $anno_corso - 2;
	$idc2 = $anni_corso_classe[0]['id_anno'];
	$desc2 = $anni_corso_classe[0]['descrizione'];
	if ($ac1 > 0) {
		$idc1 = $anni_corso_classe[1]['id_anno'];
		$desc1 = $anni_corso_classe[1]['descrizione'];
	}
	include "scegli_archivio_scrutini.html.php";
}


