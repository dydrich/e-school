<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$_REQUEST['group'] = 1;

if(isset($_REQUEST['subject'])){
	$_SESSION['__materia__'] = $_REQUEST['subject'];
}

if(!isset($_REQUEST['order'])){
	$order = "DESC";
}
else{
	$order = $_REQUEST['order'];
}
if(!isset($_REQUEST['group']) || $_REQUEST['group'] == "0"){
	$group = false;
	$link_label = "Raggruppa per mese";
	$_group = "&group=1";
}
else{
	$group = true;
	$link_label = "Non raggruppare";
	$_group = "";
}
$order_to = ($order == "DESC") ? "ASC" : "DESC";

$class = $_SESSION['__classe__']->get_ID();
$teacher = $_SESSION['__user__']->getUid();
if ($_SESSION['__user__']->isSupplyTeacher()) {
	$teacher .= ",".$_SESSION['__user__']->getUid(true);
}
$teacher_name = $_SESSION['__user__']->getFullName();
$class_name = $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$subject = $_SESSION['__materia__'];
$signature = $teacher.";".$subject;
$months = array(
	9  => array('val' => '09', 'italian' => 'settembre', 'quad' => 1),
	10 => array('val' => '10', 'italian' => 'ottobre', 'quad' => 1),
	11 => array('val' => '11', 'italian' => 'novembre', 'quad' => 1),
	12 => array('val' => '12', 'italian' => 'dicembre', 'quad' => 1),
	1  => array('val' => '01', 'italian' => 'gennaio', 'quad' => 1),
	2  => array('val' => '02', 'italian' => 'febbraio', 'quad' => 2),
	3  => array('val' => '03', 'italian' => 'marzo', 'quad' => 2),
	4  => array('val' => '04', 'italian' => 'aprile', 'quad' => 2),
	5  => array('val' => '05', 'italian' => 'maggio', 'quad' => 2),
	6  => array('val' => '06', 'italian' => 'giugno', 'quad' => 2)
);

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

if (isset($_REQUEST['month'])) {
	$q = -1;
}

switch($q){
	case 0:
		$int_time = "AND data < NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data <= '".$fine_q."'";
		$label = " primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = " secondo quadrimestre";
		break;
	default:
		$int_time = "AND MONTH(data) = ".$_REQUEST['month'];
		$label = " mese di ".$months[$_REQUEST['month']]['italian'];
		break;
}

$sel_lessons = "SELECT rb_reg_firme.*, data, materia, docente, id_classe FROM rb_reg_firme, rb_reg_classi WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND rb_reg_classi.id_reg = id_registro AND rb_reg_classi.id_classe = $class AND materia = $subject  $int_time ORDER BY data $order";
$res_lessons = $db->execute($sel_lessons);

$change_subject = new ChangeSubject("hid", "", "position: absolute; width: 180px; height: 55px; display: none", "div", $_SESSION['__subjects__']);
$change_subject->createLink("text-decoration: none; text-transform: uppercase; font-weight: bold", "left");
$change_subject->setJavascript('', 'jquery');

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Elenco lezioni". $label . " (". $res_lessons->num_rows ." ore)";

include "lessons.html.php";
