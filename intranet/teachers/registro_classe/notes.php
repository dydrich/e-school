<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

if (isset($_REQUEST['al'])) {
	$student_id = $_REQUEST['al'];
}

if(isset($_REQUEST['order'])) {
	$order = $_REQUEST['order'];
}
else {
	$order = "data";
}

if(isset($_REQUEST['q'])) {
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

if(isset($_REQUEST['tipo'])){
	$q_type = "AND tipo = ".$_REQUEST['tipo'];
}
else{
	$q_type = "";
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

switch($q){
	case 0:
		$int_time = "AND data <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data < '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_tipi = "SELECT * FROM rb_tipi_note_disciplinari ORDER BY id_tiponota";
$sel_note = "SELECT rb_note_disciplinari.*, rb_utenti.cognome, rb_utenti.nome, rb_tipi_note_disciplinari.descrizione AS tipo_nota, rb_tipi_note_disciplinari.id_tiponota FROM rb_note_disciplinari, rb_tipi_note_disciplinari, rb_utenti WHERE id_tiponota = tipo AND anno = {$_SESSION['__current_year__']->get_ID()} AND alunno IS NULL AND classe = ".$_SESSION['__classe__']->get_ID()." AND docente = uid $int_time $q_type ORDER BY $order DESC";
//print $sel_note;
try{
	$res_note = $db->executeQuery($sel_note);
	$res_tipi = $db->executeQuery($sel_tipi);
} catch (MySQLException $ex){
	$ex->redirect();
}

$navigation_label = "Registro elettronico ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione()." - Note di classe";

include "notes.html.php";
