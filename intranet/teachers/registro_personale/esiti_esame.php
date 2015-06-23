<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 23/06/15
 * Time: 9.34
 * esiti esame di licenza media
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

if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getUsername() != "rbachis") && $_SESSION['__user__']->getSchoolOrder() != 2 ){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: no_permission.php");
}
$anno = $_SESSION['__current_year__']->get_ID();
$q = 2;

$alunni = array();
$sel_alunni = "SELECT cognome, nome, id_alunno, sesso FROM rb_alunni WHERE id_classe = ". $_SESSION['__classe__']->get_ID() ." AND attivo = '1' ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}
while ($row = $res_alunni->fetch_assoc()) {
	$alunni[$row['id_alunno']] = $row;
	$alunni[$row['id_alunno']]['esito'] = "";
	$alunni[$row['id_alunno']]['id_esito'] = 0;
	$alunni[$row['id_alunno']]['id_voto'] = 0;
	$alunni[$row['id_alunno']]['voto'] = "";
}

$sel_esiti = "SELECT rb_esami_licenza.* FROM rb_esami_licenza WHERE classe = ".$_SESSION['__classe__']->get_ID();
$res_esiti = $db->executeQuery($sel_esiti);
if ($res_esiti->num_rows > 0) {
	while ($row = $res_esiti->fetch_assoc()) {
		$alunni[$row['alunno']]['esito'] = $row['esito'];
		$alunni[$row['alunno']]['id_esito'] = $row['id'];
	}
}

$sel_voti = "SELECT rb_voti_esame.* FROM rb_voti_esame WHERE classe = ".$_SESSION['__classe__']->get_ID();
$res_voti = $db->executeQuery($sel_voti);
if ($res_voti->num_rows > 0) {
	while ($row = $res_voti->fetch_assoc()) {
		$alunni[$row['alunno']]['voto'] = $row['voto'];
		$alunni[$row['alunno']]['id_voto'] = $row['id'];
	}
}

$sel_val = "SELECT * FROM rb_esiti_esame ORDER BY id";
$res_val = $db->executeQuery($sel_val);
$esiti_possibili = array();
while ($row = $res_val->fetch_assoc()) {
	$esiti_possibili[$row['id']] = $row;
}

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Esiti esame conclusivo";

include "esiti_esame.html.php";
