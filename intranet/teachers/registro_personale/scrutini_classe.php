<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getUsername() != "rbachis") && $_SESSION['__user__']->getSchoolOrder() != 2 ){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: no_permission.php");
}
$anno = $_SESSION['__current_year__']->get_ID();
$q = $_REQUEST['q'];

switch($q){
	case 1:
		$label = " primo quadrimestre";
		break;
	case 2:
		$label = " secondo quadrimestre";
}

if ($ordine_scuola == 2){
	$_REQUEST['view'] = "grade_only";
}

$modification = false;
$modification_label = "Modifica i voti";
$modification_params = "&modification=1";
$grades_only = false;
if(isset($_REQUEST['modification']) && $_REQUEST['modification'] == 1){
	$modification = true;
	$modification_label = "Termina modifica";
	$modification_params = "";
	$link = "scrutini_classe.php?q=". $q . $modification_params;
}
else if(isset($_REQUEST['view']) && $_REQUEST['view'] == "grade_only"){
	$grades_only = true;
	$link = "scrutini_classe.php?q=". $q . $modification_params;
}
else {
	$link = "scrutini_classe.php?q=". $q . $modification_params;
}

$sel_alunni = "SELECT cognome, nome, id_alunno FROM rb_alunni WHERE id_classe = ". $_SESSION['__classe__']->get_ID() ." AND attivo = '1' ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}

$sel_cls = "SELECT sezione FROM rb_classi WHERE id_classe = ".$_SESSION['__classe__']->get_ID();
$sezione = $db->executeCount($sel_cls);

$num_colonne = 1;
$first_column_width = 23;
$column_width = null;
$available_space = 100 - $first_column_width;
$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE id_materia = rb_scrutini.materia AND classe = {$_SESSION['__classe__']->get_ID()} AND quadrimestre = {$q} AND anno = {$anno} AND tipologia_scuola = {$ordine_scuola} AND rb_materie.id_materia != 26 AND rb_materie.id_materia != 30 GROUP BY rb_materie.id_materia, rb_materie.materia ORDER BY rb_materie.posizione_pagella";
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

/*
 * gestione esiti
 * solo sessione finale 
 */
if ($q == 2){
	if ($_SESSION['__classe__']->get_anno() != 3){
		$sel_es = "SELECT * FROM rb_esiti WHERE ordine_scuola = {$ordine_scuola} AND (classe = {$_SESSION['__classe__']->get_anno()} OR classe = 0) AND id_esito <> 17 ORDER BY positivo DESC";
	}
	else {
		$sel_es = "SELECT * FROM rb_esiti WHERE ordine_scuola = {$ordine_scuola} AND (classe = {$_SESSION['__classe__']->get_anno()}) ORDER BY positivo DESC";
	}	
	try{
		$res_out = $db->executeQuery($sel_es);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
}

/*
 * verifica se scrutini ancora aperti, per modifica
*/
$suffix = '';
if ($ordine_scuola == 2) {
	$suffix = '_sp';
}
$sel_scr_op = "SELECT stato_scrutinio{$suffix}, quadrimestre FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY 
quadrimestre DESC";
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
if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getUsername() != "rbachis") ){
	//$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	//header("Location: no_permission.php");
	$readonly = true;
}

/*
 * voti di comportamento scuola primaria
 */
$voti_comportamento_primaria = array("4" => array("nome" => "non adeguato", "codice" => "NA"),
			   "5" => array("nome" => "parzialmente adeguato", "codice" => "PA"),
			   "6" => array("nome" => "adeguato", "codice" => "AD")
);

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Scrutini ".$label;

include "scrutini_classe.html.php";
