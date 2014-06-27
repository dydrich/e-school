<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

$ordine_scuola = $_SESSION['__school_order__'];
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$student_id = $_REQUEST['std'];
$teacher = $_REQUEST['doc'];
$subject = $_REQUEST['subj'];

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_alunno = "SELECT id_alunno, cognome, nome, anno_corso, sezione FROM rb_alunni, rb_classi WHERE id_alunno = $student_id AND rb_alunni.id_classe = rb_classi.id_classe";
$sel_materia = "SELECT materia FROM rb_materie WHERE id_materia = {$subject}";
$sel_voti = "SELECT rb_voti.*, rb_materie.materia AS mat FROM rb_voti, rb_materie WHERE rb_materie.id_materia = rb_voti.materia AND alunno = {$student_id} AND rb_voti.materia = {$subject} AND docente = {$teacher} AND anno = {$_SESSION['__current_year__']->get_ID()} $int_time ORDER BY data_voto DESC";
$sel_teacher = "SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$teacher}";
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$materia = $db->executeCount($sel_materia);
	$res_voti = $db->executeQuery($sel_voti);
	$teacher_name = $db->executeCount($sel_teacher);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

$navigation_label = "Elenco valutazioni";

include "show_grades.html.php";