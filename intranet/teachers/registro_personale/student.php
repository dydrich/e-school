<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";
require_once "../../../lib/RBUtilities.php";

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

if(isset($_REQUEST['q'])) {
	$q = $_REQUEST['q'];
}
else {
	$q = 0;
}

if(isset($_REQUEST['subject'])) {
	$materia = $_REQUEST['subject'];
}
else {
	$materia = $_SESSION['__materia__'];
}
	
switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$note_time = "AND data <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$note_time = "AND data <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$note_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $student_id";
$sel_materia = "SELECT materia FROM rb_materie WHERE id_materia = ".$materia;
$sel_voti = "SELECT rb_voti.*, rb_tipologia_prove.label FROM rb_voti, rb_tipologia_prove WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND rb_voti.tipologia = rb_tipologia_prove.id AND rb_voti.alunno = $student_id AND materia = ".$materia." $int_time ORDER BY data_voto DESC";
$sel_note = "SELECT COUNT(id_nota) FROM rb_note_didattiche WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND alunno = $student_id AND materia = ".$materia." $note_time ORDER BY data DESC";
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_materia = $db->executeQuery($sel_materia);
	$res_voti = $db->executeQuery($sel_voti);
	$num_note = $db->executeCount($sel_note);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

$mt = $res_materia->fetch_assoc();
$desc_materia = $mt['materia'];

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

$array_voti = array();
while($row = $res_voti->fetch_assoc()){
	array_push($array_voti, $row['voto']);
}

$mat = $_SESSION['__user__']->getSubject();
$class = $_SESSION['__classe__']->get_ID();

$change_subject = new ChangeSubject("hid", "", "position: absolute; width: 180px; height: 55px; display: none", "div", $_SESSION['__subjects__']);
$change_subject->createLink("text-decoration: none; text-transform: uppercase; font-weight: bold");
$change_subject->setJavascript("", "jquery");

$messages = array("Voto inserito correttamente", "Voto non inserito", "Voto modificato correttamente", "Voto non modificato", "Voto eliminato correttamente", "Voto non eliminato");
$msg = "";
if(isset($_REQUEST['msg'])){
	$msg = $messages[$_REQUEST['msg']];
}

$selected = array();
if (isset($_SESSION['__user_config__']['tipologia_prove'])){
	$selected = $_SESSION['__user_config__']['tipologia_prove'];
}
if (count($selected) > 0){
	$sel_prove = "SELECT * FROM rb_tipologia_prove WHERE id IN (".join(",", $selected).")";
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

$navigation_label = "Registro personale del docente - Classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "student.html.php";
