<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 01/05/15
 * Time: 19.22
 */
require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$ordine_scuola = $_SESSION['__school_order__'];
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = setNavigationLabel($ordine_scuola);
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

$sel_tipi = "SELECT * FROM rb_tipi_note_disciplinari ORDER BY id_tiponota";
$sel_note = "SELECT rb_note_disciplinari.*, rb_utenti.cognome, rb_utenti.nome, rb_tipi_note_disciplinari.descrizione AS tipo_nota, rb_tipi_note_disciplinari.id_tiponota FROM rb_note_disciplinari LEFT JOIN rb_utenti ON docente = uid, rb_tipi_note_disciplinari WHERE id_tiponota = tipo AND anno = {$_SESSION['__current_year__']->get_ID()} AND classe = ".$_SESSION['__classe__']->get_ID()." AND alunno IS NULL AND docente = uid AND tipo <> 2 ORDER BY data DESC";
try{
	$res_note = $db->executeQuery($sel_note);
	$res_tipi = $db->executeQuery($sel_tipi);
} catch (MySQLException $ex){
	$ex->redirect();
}
$drawer_label = "Elenco note disciplinari, classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "note_classe.html.php";
