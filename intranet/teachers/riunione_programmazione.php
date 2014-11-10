<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 08/09/14
 * Time: 16.39
 */
require_once "../../lib/start.php";
require_once "../../lib/PlanningMeeting.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "registro elettronico";
$drawer_label = "Riunione di programmazione ";
if (isset($planMeet)) {
	$drawer_label .= " del " . format_date($planMeet->getMeetingDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/");
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];

$rid = $_REQUEST['rid'];

if ($rid != 0) {
	$res = $db->executeQuery("SELECT * FROM rb_riunioni_programmazione WHERE id_riunione = ".$rid);
	$data = $res->fetch_assoc();
	$planMeet = new \eschool\PlanningMeeting($rid, $data, new MySQLDataLoader($db));
}

include "riunione_programmazione.html.php";
