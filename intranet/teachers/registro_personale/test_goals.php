<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

setlocale(LC_TIME, "it_IT");

$uid = $_SESSION['__user__']->getUid();
$class = $_SESSION['__classe__']->get_ID();
$anno = $_SESSION['__current_year__']->get_ID();

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$sel_test = "SELECT rb_verifiche.*, rb_tipologia_prove.tipologia AS tipo FROM rb_verifiche, rb_tipologia_prove WHERE id_verifica = {$_REQUEST['idv']} AND rb_verifiche.tipologia = rb_tipologia_prove.id";
$res_test = $db->execute($sel_test);
$test = $res_test->fetch_assoc();
$giorno_str = strftime("%A %d %B %H:%M", strtotime($test['data_verifica']));

$sel_sub = "SELECT * FROM rb_materie WHERE id_materia = ".$_SESSION['__materia__'];
$res_sub = $db->execute($sel_sub);
$materia = $res_sub->fetch_assoc();

$selected = $_SESSION['__user_config__']['tipologia_prove'];
if (count($selected) > 0){
	$sel_prove = "SELECT * FROM rb_tipologia_prove WHERE id IN (".implode(",", $selected).")";
}
else{
	$sel_prove = "SELECT * FROM rb_tipologia_prove WHERE `default` = 1";
}
try {
	$res_prove = $db->executeQuery($sel_prove);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}

$query = "SELECT rb_obiettivi.* FROM rb_obiettivi LEFT JOIN rb_obiettivi_classe ON rb_obiettivi.id = id_obiettivo WHERE docente = {$uid} AND rb_obiettivi.anno = {$anno} AND rb_obiettivi_classe.classe = {$class} AND materia = {$materia['id_materia']} ORDER BY id_padre";
try {
	$res = $db->executeQuery($query);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}
$goals = array();
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

$sel_obj = "SELECT * FROM rb_obiettivi_verifica WHERE id_verifica = {$_REQUEST['idv']}";
$res_obj = $db->executeQuery($sel_obj);
$obj = array();
while ($row = $res_obj->fetch_assoc()){
	$obj[] = $row['id_obiettivo'];
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

include "test_goals.html.php";