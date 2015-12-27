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

$student_id = $_REQUEST['stid'];

if(isset($_REQUEST['order']))
	$order = $_REQUEST['order'];
else
	$order = "data";

if(isset($_REQUEST['q']))
	$q = $_REQUEST['q'];
else{
	$q = 0;
}

if(isset($_REQUEST['subject'])) {
	$materia = $_REQUEST['subject'];
}
else {
	$materia = $_SESSION['__materia__'];
}
$_SESSION['__materia__'] = $materia;

if(isset($_REQUEST['tipo'])){
	$q_type = "AND tipo = ".$_REQUEST['tipo'];
}
else{
	$q_type = "";
}

switch($q){
	case 0:
		$int_time = "AND data <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $student_id";
$sel_materia = "SELECT materia FROM rb_materie WHERE id_materia = $materia";
$sel_tipi = "SELECT * FROM rb_tipi_note_didattiche ORDER BY id_tiponota";
$sel_note = "SELECT rb_note_didattiche.*, rb_tipi_note_didattiche.descrizione AS tipo_nota FROM rb_note_didattiche, rb_tipi_note_didattiche WHERE id_tiponota = tipo AND alunno = $student_id AND materia = ".$_SESSION['__materia__']." AND anno = {$_SESSION['__current_year__']->get_ID()} $int_time $q_type ORDER BY $order DESC";
//print $sel_voti;
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_materia = $db->executeQuery($sel_materia);
	$res_note = $db->executeQuery($sel_note);
	$res_tipi = $db->executeQuery($sel_tipi);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

$mt = $res_materia->fetch_assoc();
$desc_materia = $mt['materia'];

$navigation_label = "registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Elenco note didattiche";

include "student_notes.html.php";
