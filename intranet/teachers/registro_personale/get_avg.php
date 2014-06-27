<?php

require_once "../../../lib/start.php";
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

$quadrimestre = $_REQUEST['q'];
$anno = $_SESSION['__current_year__']->get_ID();
$teacher = $_SESSION['__user__']->getUid();
$class = $_SESSION['__classe__']->get_ID();
$materia = $_SESSION['__materia__'];

switch($quadrimestre){
	case 0:
		$int_time = "AND data_voto < NOW()";
		$abs_time = "AND data < NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$abs_time = "AND data <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$abs_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = ", secondo quadrimestre";
}

if($_REQUEST['param'] == "avg")
	$sel = "SELECT ROUND(AVG(voto), 2) FROM rb_scrutini WHERE anno = $anno AND materia = $materia AND classe = $class AND quadrimestre = $quadrimestre";
else
	$sel = "SELECT ROUND(AVG(voto), 2) FROM rb_voti WHERE anno = $anno AND materia = $materia AND docente = $teacher $int_time";

$avg = $db->executeCount($sel);
$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
if ($materia == 26 || $materia == 30){
	$avg = $voti_religione[RBUtilities::convertReligionGrade($avg)];
}
		
header("Content-type: text/plain");
if($_REQUEST['param'] == "avg")
	print "$avg";
else
	print "($avg)";
exit;

?>