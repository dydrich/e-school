<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 16/02/15
 * Time: 18.32
 * medie voto per l'alunno H
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

$sel_voti = "SELECT ROUND(AVG(voto), 2) AS voto, materia, alunno FROM rb_voti, rb_alunni WHERE alunno = id_alunno AND alunno = $id_alunno AND id_classe = {$_SESSION['__classe__']->get_ID()} AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time GROUP BY materia, alunno ORDER BY alunno, materia ";
try{
	$res_voti = $db->executeQuery($sel_voti);
} catch (MySQLException $ex){
	$ex->redirect();
}
$sum = 0;
$materie = 0;
while ($r = $res_voti->fetch_assoc()){
	$alunno['voti'][$r['materia']] = $r['voto'];
	$idalunno = $r['alunno'];
	$materie++;
	$sum += $r['voto'];
}
if ($materie > 0){
	$val = $sum / $materie;
	$alunno['media'] = round($val, 2);
}

$alunni = array();
$alunni[0] = $alunno;

$sel_cls = "SELECT musicale FROM rb_classi WHERE id_classe = ".$_SESSION['__classe__']->get_ID();
$musicale = $db->executeCount($sel_cls);

$num_colonne = 1;
$first_column_width = 25;
$column_width = null;
$available_space = 100 - $first_column_width;
$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE id_materia = rb_scrutini.materia AND id_materia <> 40 AND classe = {$_SESSION['__classe__']->get_ID()} ".$scr_par." AND anno = {$anno} AND id_materia > 2 AND tipologia_scuola = {$ordine_scuola} GROUP BY rb_materie.id_materia, rb_materie.materia ORDER BY rb_materie.id_materia";
try {
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex) {
	$ex->redirect();
}
if ($res_materie->num_rows < 1) {
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie WHERE pagella = 1 AND id_materia > 2 AND tipologia_scuola = {$ordine_scuola}";
	if($musicale != "1"){
		$sel_materie .= " AND id_materia <> 13 ";
	}
	$sel_materie .= "ORDER BY id_materia";
	$res_materie = $db->executeQuery($sel_materie);
}
$materie = array();
while($materia = $res_materie->fetch_assoc()){
	if($materia['materia'] == "Scienze motorie")
		$materia['materia'] = "Smotorie";
	$materie[$materia['id_materia']] = $materia;
}

$num_materie = $res_materie->num_rows;
$num_colonne += $num_materie;
$column_width = intval($available_space / ($num_colonne - 1));

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Riepilogo medie generali ".$label;

include "medie_voto.html.php";
