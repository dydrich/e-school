<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/11/14
 * Time: 23.06
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/ClassbookData.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$stid = $_REQUEST['stid'];

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && (!$_SESSION['__user__']->isAdministrator()) && ($_SESSION['__user__']->getUsername() != "rbachis")){
	$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
	header("Location: no_permission.php");
}

if(isset($_REQUEST['q']))
	$q = $_REQUEST['q'];
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

$_tday = date("Y-m-d");

switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$scr_par = "";
		$par_tot = "AND data <= NOW()";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$scr_par = "AND quadrimestre = {$q}";
		$label .= "- primo quadrimestre";
		$fq = $fine_q;
		$min = $_tday < $fq ? $_tday : $fq;
		$par_tot = "AND data <= '{$min}'";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$scr_par = "AND quadrimestre = {$q}";
		$label .= "- secondo quadrimestre";
		$par_tot = "AND (data > '".$fine_q."' AND data <= NOW()) ";
}

/*
 * assenze
 */
$module = $_SESSION['__classe__']->get_modulo_orario();
$classbook_data = new ClassbookData($_SESSION['__classe__'], $school_year, $par_tot, $db);
$totali = $classbook_data->getClassSummary();
$students = $classbook_data->getStudentsSummary();
$studentData = $students[$stid];
$perc_day = round((($studentData['absences'] / $totali['giorni']) * 100), 2);
$absences = new RBTime(0, 0, 0);
$absences->setTime($totali['ore']->getTime() - $studentData['presence']->getTime());
$perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);
if($perc_day == 0){
	$perc_day = "--";
}
else{
	$perc_day .= "%";
}
if($perc_hour == 0){
	$perc_hour = "--";
}
else{
	$perc_hour .= "%";
}

/*
 * note disciplinari
 */
$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $stid";
$sel_tipi = "SELECT * FROM rb_tipi_note_disciplinari ORDER BY id_tiponota";
$sel_note = "SELECT rb_note_disciplinari.*, rb_utenti.cognome, rb_utenti.nome, rb_tipi_note_disciplinari.descrizione AS tipo_nota, rb_tipi_note_disciplinari.id_tiponota FROM rb_note_disciplinari, rb_tipi_note_disciplinari, rb_utenti WHERE id_tiponota = tipo AND alunno = {$stid} AND docente = uid ".$par_tot." AND anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY data DESC";
//print $sel_note;
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_note = $db->executeQuery($sel_note);
	$res_tipi = $db->executeQuery($sel_tipi);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

$drawer_label = "Scheda riepilogativa di ".$alunno['cognome']." ".$alunno['nome'];

$label_notes_dis = "Nessuna nota presente";
if ($res_note->num_rows > 0) {
	$label_notes_dis = "Sono presenti ".$res_note->num_rows." note";
}

setlocale(LC_TIME, "it_IT.utf8");

include "scheda_alunno.html.php";
