<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";
require_once "../../lib/StudentActivityReport.php";
require_once "../../lib/RBUtilities.php";
require_once "../../lib/ClassbookData.php";
require_once "../../lib/RBTime.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

// pagina da ricaricare in header.php
$page = "index.php";

include "check_sons.php";

if (count($_SESSION['__sons__'])  < 1) {
	include "no_sons.php";
	exit;
}

$utils = SessionUtils::getInstance($db);
$utils->registerCurrentClassFromUser($_SESSION['__current_son__'], "__classe__");

$rb = RBUtilities::getInstance($db);
$student = $rb->loadUserFromUid($_SESSION['__current_son__'], "student");

$school_year = $_SESSION['__school_year__'][$_SESSION['__classe__']->getSchoolOrder()];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$today = date("Y-m-d");

$preschool = false;
if($today < $inizio_lezioni){
    $preschool = true;
}

if ($student && $student->isActive() && !$preschool) {
	$stAcR = new \eschool\StudentActivityReport($student, 15, new MySQLDataLoader($db));
	$activities = $stAcR->getActivities();
	$has_report = $stAcR->checkMonthlyReport();
	$avvisi = [];
	if ($has_report) {
		$avvisi[] = ["warning", "Nell'ultimo consiglio di classe sono state segnalate delle insufficienze", "ins"];
	}

	$school_year = $_SESSION['__school_year__'][$_SESSION['__classe__']->getSchoolOrder()];
	$classbook_data = new ClassbookData($_SESSION['__classe__'], $school_year, "AND data <= NOW()", $db);
	$totali = $classbook_data->getClassSummary();
	$presence = $classbook_data->getStudentSummary($student->getUid());
	$absences = new RBTime(0, 0, 0);
	$absences->setTime($totali['ore']->getTime() - $presence['presence']->getTime());
	$perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);
	if ($perc_hour > 25) {
		$avvisi[] = ["warning", "Attenzione: la percentuale di ore di assenza &egrave; superiore al 25%, limite massimo per la validazione dell'anno ai sensi dell’articolo 11, comma 1, del Decreto legislativo n. 59 del 2004, e successive modificazioni", ""];
	}
	else if ($perc_hour > 19) {
		$avvisi[] = ["info", "Attenzione alle assenze, che potrebbero compromettere la validazione dell'anno scolastico", ""];
	}
}

$ticker_height = 100;

$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$drawer_label = "Home page";

include "index.html.php";
