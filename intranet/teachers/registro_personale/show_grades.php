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

$student_id = $_REQUEST['alunno'];

if(isset($_REQUEST['q']))
	$q = $_REQUEST['q'];
else{
	$q = 0;
}

switch($q){
	case 0:
		$int_time = "AND data_voto < NOW()";
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

$sel_voti = "SELECT rb_voti.* FROM rb_voti WHERE rb_voti.alunno = $student_id AND materia = ".$_SESSION['__materia__']." $int_time ORDER BY data_voto DESC";
try{
	$res_voti = $db->executeQuery($sel_voti);
} catch (MySQLException $ex){
	echo $ex->getMessage().";".$ex->getQuery();
	exit;
}

$array_voti = array();
while($row = $res_voti->fetch_assoc()){
	array_push($array_voti, $row['voto']);
}

include "show_grades.html.php";

?>