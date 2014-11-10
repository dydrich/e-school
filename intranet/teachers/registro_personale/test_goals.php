<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Test.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

setlocale(LC_TIME, "it_IT.utf8");

$uid = $_SESSION['__user__']->getUid();
$class = $_SESSION['__classe__']->get_ID();
$anno = $_SESSION['__current_year__']->get_ID();

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$sel_test = "SELECT rb_verifiche.*, rb_tipologia_prove.tipologia AS tipo FROM rb_verifiche, rb_tipologia_prove WHERE id_verifica = {$_REQUEST['idv']} AND rb_verifiche.tipologia = rb_tipologia_prove.id";
$res_test = $db->execute($sel_test);
$_test = $res_test->fetch_assoc();

$test = new \eschool\Test($_REQUEST['idv'], new MySQLDataLoader($db), $_test, false);

$selected = array();
$sel_prove = "SELECT * FROM rb_tipologia_prove ";
try {
	$res_prove = $db->executeQuery($sel_prove);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}
while ($row = $res_prove->fetch_assoc()) {
	$prove[$row['id']] = $row['tipologia'];
}

$query = "SELECT rb_obiettivi.* FROM rb_obiettivi LEFT JOIN rb_obiettivi_classe ON rb_obiettivi.id = id_obiettivo WHERE docente = {$uid} AND rb_obiettivi.anno = {$anno} AND rb_obiettivi_classe.classe = {$class} AND materia = {$_SESSION['__materia__']} ORDER BY id_padre";
try {
	$res = $db->executeQuery($query);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}
$goals = array();
while ($row = $res->fetch_assoc()){
	if ($row['id_padre'] == ""){
		if (!isset($goals[$row['id']])){
			$goals[$row['id']] = $row;
		}
		if (isset($grades[$row['id']])){
			$goals[$row['id']]['grade'] = $grades[$row['id']];
		}
		else {
			$goals[$row['id']]['grade'] = 0;
		}
	}
	else {
		if (!isset($goals[$row['id_padre']]['children'])){
			$goals[$row['id_padre']]['children'] = array();
		}
		if (!isset($goals[$row['id_padre']]['children'][$row['id']])){
			$goals[$row['id_padre']]['children'][$row['id']] = $row;
		}
		if (isset($grades[$row['id']])){
			$goals[$row['id_padre']]['children'][$row['id']]['grade'] = $grades[$row['id']];
		}
		else {
			$goals[$row['id_padre']]['children'][$row['id']]['grade'] = 0;
		}
	}
}

if (isset($_REQUEST['q'])){
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

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Gestione obiettivi verifica";

include "test_goals.html.php";
