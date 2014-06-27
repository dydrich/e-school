<?php

/*
 * assegna la classe allo studente
 */

require_once "../../lib/start.php";
require_once "../../lib/StudentManager.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", "0");

list($classe, $ordine_scuola) = explode(";", $_REQUEST['cls']);
$studente = $_REQUEST['stud_id'];
$old_classe = $_REQUEST['old_cls'];
$id_anno = $_SESSION['__current_year__']->get_ID();

header("Content-type: text/plain");

$sel_reg = "SELECT COUNT(rb_reg_alunni.id_registro) FROM rb_reg_alunni, rb_reg_classi WHERE id_anno = $id_anno AND id_registro = id_reg AND id_alunno = {$studente}";
$check_data1 = "SELECT COUNT(*) FROM rb_scrutini WHERE anno = $id_anno AND quadrimestre = 1 AND alunno = {$studente}";
$exist_reg = $exist_rep = 0;
try{
	$exist_reg = $db->executeCount($sel_reg);
	$exist_rep = $db->executeCount($check_data1);
} catch (MySQLException $ex){
	print "kosql;".$ex->getQuery().";".$ex->getMessage();
	exit;
}

$studentBean = new Student($studente, "", "", "", "", "");
$studentBean->setOldClass($old_classe);
$studentBean->setClass($classe);
$student_manager = new StudentManager($db, $studentBean);
$student_manager->setExistClassbook($exist_reg);
$student_manager->setExistReport($exist_rep);
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$student_manager->setSchoolYear($school_year);

try{
	$student_manager->changeClass();
} catch (MySQLException $ex){
	// TODO: continuare se errore di duplicazione
	print "kosql;".$ex->getQuery().";".$ex->getMessage();
	exit;
}
print "ok";
exit;