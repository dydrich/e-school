<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
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

$sel_alunni = "SELECT cognome, nome, id_alunno FROM rb_alunni WHERE id_classe = ". $_SESSION['__classe__']->get_ID() ." ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}

$sel_cls = "SELECT sezione FROM rb_classi WHERE id_classe = ".$_SESSION['__classe__']->get_ID();
$sezione = $db->executeCount($sel_cls);

$sel_param = "SELECT * FROM rb_parametri_pagella WHERE ordine_scuola = {$ordine_scuola}";
$res_param = $db->executeQuery($sel_param);

$giudizi = array();
$sel_giudizi = "SELECT * FROM rb_giudizi_parametri_pagella ORDER BY id_parametro";
$res_giudizi = $db->executeQuery($sel_giudizi);
while ($row = $res_giudizi->fetch_assoc()){
	if (!isset($giudizi[$row['id_parametro']])){
		$giudizi[$row['id_parametro']] = array();
	}
	$giudizi[$row['id_parametro']][$row['id']] = $row['giudizio'];
}

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Livello globale di maturazione ".$label;

include "parametri_pagella.html.php";
