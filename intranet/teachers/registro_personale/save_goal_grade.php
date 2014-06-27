<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$docente = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$materia = $_SESSION['__materia__'];

$goal = $_REQUEST['goal'];
$grade = $_REQUEST['grade'];
$gradeID = $_REQUEST['gradeID'];

try {
	$exists_goal_grade = $db->executeCount("SELECT id FROM rb_voti_obiettivo WHERE id_voto = {$gradeID} AND obiettivo = {$goal}");
	
	if ($grade == 0) {
		if ($exists_goal_grade) {
			$db->executeUpdate("DELETE FROM rb_voti_obiettivo WHERE id = {$exists_goal_grade}");	
		}
	}
	else {
		if ($exists_goal_grade) {
			//echo "UPDATE rb_voti_obiettivo SET voto = {$grade} WHERE id = {$exists_goal_grade}";
			$db->executeUpdate("UPDATE rb_voti_obiettivo SET voto = {$grade} WHERE id = {$exists_goal_grade}");
		}
		else {
			$db->executeUpdate("INSERT INTO rb_voti_obiettivo (id_voto, obiettivo, voto) VALUES ({$gradeID}, {$goal}, {$grade})");
		}
	}
} catch (MySQLException $ex) {
	echo "kosql;".$ex->getMessage().";".$ex->getQuery();
	exit;
}

header("Content-type: text/plain");
echo "ok";
exit;