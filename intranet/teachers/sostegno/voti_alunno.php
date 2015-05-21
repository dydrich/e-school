<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 21/05/15
 * Time: 19.04
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

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

if(!isset($_REQUEST['cls'])){
	$_REQUEST['cls'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}

if(isset($_REQUEST['q'])) {
	$q = $_REQUEST['q'];
}
else{
	if(date("Y-m-d") > $fine_q){
		$q = 2;
	}
	else{
		$q = 1;
	}
}

$subj = $_REQUEST['subj'];

$label = "";
$anno = $_SESSION['__current_year__']->get_ID();

switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$scr_par = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$scr_par = "AND quadrimestre = {$q}";
		$label .= "- primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$scr_par = "AND quadrimestre = {$q}";
		$label .= "- secondo quadrimestre";
}

if (isset($_SESSION['__sp_student__']['dati']['id'])){
	$idd = $_SESSION['__sp_student__']['dati']['id'];
}
$id_alunno = $_SESSION['__sp_student__']['alunno'];

$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE id_alunno = $id_alunno ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = array();
while ($row = $res_alunni->fetch_assoc()){
	$alunno = $row;
	$alunno['voti'] = array();
}

$sel_voti = "SELECT voto, data_voto, descrizione, argomento FROM rb_voti WHERE alunno = $id_alunno AND materia = $subj AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ORDER BY data_voto DESC ";
try{
	$res_voti = $db->executeQuery($sel_voti);
} catch (MySQLException $ex){
	$ex->redirect();
}

$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE id_materia = rb_scrutini.materia AND id_materia <> 40 AND classe = {$_SESSION['__classe__']->get_ID()} ".$scr_par." AND anno = {$anno} AND id_materia > 2 AND tipologia_scuola = {$ordine_scuola} GROUP BY rb_materie.id_materia, rb_materie.materia ORDER BY rb_materie.id_materia";
try {
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex) {
	$ex->redirect();
}

$materie = array();
while($materia = $res_materie->fetch_assoc()){
	if($materia['materia'] == "Scienze motorie")
		$materia['materia'] = "Smotorie";
	$materie[$materia['id_materia']] = array("id" => $materia['id_materia'], "mat" => $materia['materia'], "media" => 0);
}

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Dettaglio voti ".$label;

include "voti_alunno.html.php";
