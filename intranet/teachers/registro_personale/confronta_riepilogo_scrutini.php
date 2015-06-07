<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/06/15
 * Time: 10.10
 * confronta il ripeilogo scrutini tra primo e secondo quadrimestre
 * solo per docenti che hanno almeno 2 materie nella classe
 */
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

$q = 2;

$alunni = array();
$subjects = "";
$sel_alunni = "SELECT cognome, nome, id_alunno FROM rb_alunni WHERE id_classe = ". $_SESSION['__classe__']->get_ID() ." AND attivo = '1' ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}
while ($row = $res_alunni->fetch_assoc()) {
	$alunni[$row['id_alunno']] = array("alunno" => $row['cognome']." ".$row['nome'], "voti" => array());
	foreach ($_SESSION['__subjects__'] as $sub) {
		$alunni[$row['id_alunno']]['voti'][$sub['id']] = array("1q" => 0, "2q" => 0);
	}
	$alunni[$row['id_alunno']]['voti']['media'] = array("1q" => 0, "2q" => 0);
}

reset ($_SESSION['__subjects__']);
foreach ($_SESSION['__subjects__'] as $sub) {
	$subjects[] = $sub['id'];
}
$subjects_param = implode(", ", $subjects);

$sel_voti = "SELECT alunno, materia, voto, quadrimestre FROM rb_scrutini WHERE classe = ". $_SESSION['__classe__']->get_ID() ." AND materia IN ($subjects_param) AND anno = ".$_SESSION['__current_year__']->get_ID();
$res_voti = $db->executeQuery($sel_voti);
while ($grade = $res_voti->fetch_assoc()) {
	if ($grade['voto'] != "") {
		if ($grade['quadrimestre'] == 1) {
			$alunni[$grade['alunno']]['voti'][$grade['materia']]['1q'] = $grade['voto'];
			$alunni[$grade['alunno']]['voti']['media']['1q'] += $grade['voto'];
		}
		else if ($grade['quadrimestre'] == 2) {
			$alunni[$grade['alunno']]['voti'][$grade['materia']]['2q'] = $grade['voto'];
			$alunni[$grade['alunno']]['voti']['media']['2q'] += $grade['voto'];
		}
	}
}

$first_column = $other_column = 0;
$num_subject = count($_SESSION['__subjects__']);
if($num_subject == 2){
	$first_column = 46;
	$other_column = 9;
}
else if($num_subject == 3){
	$first_column = 36;
	$other_column = 8;
}
else if($num_subject == 4){
	$first_column = 36;
	$other_column = 8;
}

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Confronto riepilogo scrutini";

include "confronta_riepilogo_scrutini.html.php";
