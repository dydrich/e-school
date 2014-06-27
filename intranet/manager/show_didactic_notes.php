<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__school_order__'];
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$student_id = $_REQUEST['std'];
$teacher = $_REQUEST['doc'];

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
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

$sel_alunno = "SELECT id_alunno, cognome, nome, anno_corso, sezione FROM rb_alunni, rb_classi WHERE id_alunno = $student_id AND rb_alunni.id_classe = rb_classi.id_classe";
$sel_materia = "SELECT id_materia, materia FROM rb_materie WHERE tipologia_scuola = {$ordine_scuola}";
$sel_note = "SELECT rb_note_didattiche.*, rb_tipi_note_didattiche.descrizione AS tipo_nota, rb_materie.materia AS mat FROM rb_note_didattiche, rb_tipi_note_didattiche, rb_materie WHERE id_tiponota = tipo AND rb_materie.id_materia = rb_note_didattiche.materia AND alunno = {$student_id} AND docente = {$teacher} AND anno = {$_SESSION['__current_year__']->get_ID()} $int_time $q_type ORDER BY data DESC";
$sel_teacher = "SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$teacher}";
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_materia = $db->executeQuery($sel_materia);
	$res_note = $db->executeQuery($sel_note);
	$teacher_name = $db->executeCount($sel_teacher);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

$materie = array();
while ($mt = $res_materia->fetch_assoc()){
	$materia[$mt['id_materia']] = $mt['materia'];
}

$navigation_label = "Elenco note didattiche";

include "show_didactic_notes.html.php";