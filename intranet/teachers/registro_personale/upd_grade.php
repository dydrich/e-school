<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$id_alunno = $_REQUEST['alunno'];
if(isset($_REQUEST['subj'])) {
	$materia = $_REQUEST['subj'];
}
else {
	$materia = $_SESSION['__materia__'];
}
/*
 * materia alternativa
 */
if ($materia == 46) {
	$materia = 26;
}
else if ($materia == 47) {
	$materia = 30;
}

$q = $_REQUEST['q'];
$anno = $_SESSION['__current_year__']->get_ID();
$grade = $_REQUEST['grade'];

$avg = 0;
$upd = "UPDATE rb_scrutini SET voto = $grade WHERE alunno = $id_alunno AND materia = $materia AND anno = $anno AND quadrimestre = $q";
$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM rb_scrutini WHERE alunno = $id_alunno AND anno = $anno AND quadrimestre = $q AND materia != 26";
$sel_avg2 = "SELECT ROUND(AVG(CASE WHEN voto < 6 THEN 6 ELSE voto END), 2) FROM rb_scrutini WHERE alunno = $id_alunno AND anno = $anno AND quadrimestre = $q AND materia != 26";
try{
	$ret = $db->executeUpdate($upd);
	$avg = $db->executeCount($sel_avg);
	$avg2 = $db->executeCount($sel_avg2);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$response['avg'] = $avg;
$response['avg2'] = $avg2;

echo json_encode($response);
exit;
