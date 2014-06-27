<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 28/05/14
 * Time: 18.32
 */

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";
require_once "../../../lib/RBUtilities.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$mat = $_SESSION['__user__']->getSubject();

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

$change_subject = new ChangeSubject("hid", "", "position: absolute; width: 180px; height: 105px; display: none", "div", $_SESSION['__subjects__']);
$change_subject->createLink("text-decoration: none; text-transform: uppercase; font-weight: bold", "left");

if(isset($_REQUEST['subject']))
	$_SESSION['__materia__'] = $_REQUEST['subject'];

$sel_dati = "SELECT rb_alunni.cognome, rb_alunni.nome, alunno, voto, quadrimestre FROM rb_alunni LEFT JOIN rb_scrutini ON id_alunno = alunno WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND id_classe = ". $_SESSION['__classe__']->get_ID() ." AND classe = id_classe AND materia = ".$_SESSION['__materia__']." ORDER BY cognome, nome";
$res_dati = $db->execute($sel_dati);
$dati = array();
while ($row = $res_dati->fetch_assoc()){
	if (!isset($dati[$row['alunno']])){
		$dati[$row['alunno']] = array('lname' => $row['cognome'], 'fname' => $row['nome'], 'voto1q' => 0, 'voto2q' =>0);
	}
	if ($row['quadrimestre'] == 1){
		$dati[$row['alunno']]['voto1q'] = $row['voto'];
	}
	else {
		$dati[$row['alunno']]['voto2q'] = $row['voto'];
	}
}

$avg1 = $db->executeCount("SELECT ROUND(AVG(voto), 2) FROM rb_scrutini WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND classe = ". $_SESSION['__classe__']->get_ID() ." AND materia = ".$_SESSION['__materia__']." AND quadrimestre = 1");
$avg2 = $db->executeCount("SELECT ROUND(AVG(voto), 2) FROM rb_scrutini WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND classe = ". $_SESSION['__classe__']->get_ID() ." AND materia = ".$_SESSION['__materia__']." AND quadrimestre = 2");

$navigation_label = "Registro personale del docente - Classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "confronta_scrutini.html.php";