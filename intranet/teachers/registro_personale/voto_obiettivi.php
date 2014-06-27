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

$goals = array();

$uid = $_SESSION['__user__']->getUid();
$class = $_SESSION['__classe__']->get_ID();
$anno = $_SESSION['__current_year__']->get_ID();

$student_id = $_REQUEST['stid'];
$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = {$student_id}";

$materia = $_SESSION['__materia__'];
$sel_materia = "SELECT materia FROM rb_materie WHERE id_materia = ".$materia;

$sel_voto = "SELECT rb_voti.*, rb_tipologia_prove.label FROM rb_voti, rb_tipologia_prove WHERE rb_voti.tipologia = rb_tipologia_prove.id AND id_voto = ".$_REQUEST['idv'];
$query = "SELECT rb_obiettivi.* FROM rb_obiettivi LEFT JOIN rb_obiettivi_classe ON rb_obiettivi.id = id_obiettivo WHERE docente = {$uid} AND rb_obiettivi.anno = {$anno} AND rb_obiettivi_classe.classe = {$class} AND materia = {$materia} ORDER BY id_padre";
$sel_grades = "SELECT * FROM rb_voti_obiettivo WHERE id_voto = {$_REQUEST['idv']}";

try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_materia = $db->executeQuery($sel_materia);
	$res_voto = $db->executeQuery($sel_voto);
	$res = $db->executeQuery($query);
	$res_grades = $db->executeQuery($sel_grades);
	
} catch (MySQLException $ex){
	$ex->redirect();
}

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

$alunno = $res_alunno->fetch_assoc();
$mt = $res_materia->fetch_assoc();
$desc_materia = $mt['materia'];
$voto = $res_voto->fetch_assoc();
if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
	$voto['voto'] = $voti_religione[$voto['voto']];
}

$grades = array();
if ($res_grades->num_rows > 0){
	while ($g = $res_grades->fetch_assoc()){
		$grades[$g['obiettivo']] = $g['voto'];
	}
}

while ($row = $res->fetch_assoc()){
	if ($row['id_padre'] == ""){
		if (!$goals[$row['id']]){
			$goals[$row['id']] = $row;
		}
		if ($grades[$row['id']]){
			$goals[$row['id']]['grade'] = $grades[$row['id']];
		}
		else {
			$goals[$row['id']]['grade'] = 0;
		}
	}
	else {
		if (!$goals[$row['id_padre']]['children']){
			$goals[$row['id_padre']]['children'] = array();
		}
		if (!$goals[$row['id_padre']]['children'][$row['id']]){
			$goals[$row['id_padre']]['children'][$row['id']] = $row;
		}
		if ($grades[$row['id']]){
			$goals[$row['id_padre']]['children'][$row['id']]['grade'] = $grades[$row['id']];
		}
		else {
			$goals[$row['id_padre']]['children'][$row['id']]['grade'] = 0;
		}
	}
}

if ($_REQUEST['q']){
	$q = $_REQUEST['q'];
}
else {
	if (date("Y-m-d") <= $fine_q){
		$q = 1;
	}
	else {
		$q = 2;
	}
}

include "voto_obiettivi.html.php";

?>