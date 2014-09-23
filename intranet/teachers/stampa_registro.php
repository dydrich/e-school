<?php

require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";
require_once "../../lib/TeacherRecordBookManager.php";
require_once "../../lib/RBUtilities.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "Registro elettronico - Stampa registro personale";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$isSuppplyTeacher = false;
$rb = RBUtilities::getInstance($db);
if ($_SESSION['__user__']->isSupplyTeacher()) {
	$tit = $_SESSION['__user__']->getLecturer();
	$user = $rb->loadUserFromUid($tit, 'school');
	$isSuppplyTeacher = true;
}
else {
	$user = $_SESSION['__user__'];
}

$path = $_SESSION['__path_to_root__']."/download/registri/".$_SESSION['__current_year__']->get_ID()."/docenti/".$user->getUid()."/";
$log_manager = new TeacherRecordBookManager($user, $db, $path, $_SESSION['__current_year__'], $_SESSION['__school_year__'][$ordine_scuola]);

$classi = $log_manager->getRecordBooks();
//print_r($classi);
$supplyClasses = $_SESSION['__user__']->getClasses();
$ids = array_keys($supplyClasses);

if ($ordine_scuola == 1){
	if ($_SESSION['__user__']->getSubject() != 12 && $_SESSION['__user__']->getSubject() != 9){
		$sel_mat = "SELECT materia FROM rb_materie WHERE id_materia = {$_SESSION['__user__']->getSubject()}";
		$materia = $db->executeCount($sel_mat);
	}
}
else if ($ordine_scuola == 2){
	
}

/*
 * per il download del registro
*/
$_SESSION['no_file'] = array("referer" => "intranet/teachers/stampa_registro.php", "path" => "intranet/teachers/", "relative" => "stampa_registro.php");

include "stampa_registro.html.php";
