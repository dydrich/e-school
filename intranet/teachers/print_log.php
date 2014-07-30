<?php

require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";
require_once "../../lib/TeacherRecordBookManager.php";
require_once "../../lib/SessionUtils.php";
require_once "../../lib/RBUtilities.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "Registro elettronico - Stampa registro personale";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$school_order_directory = "scuola_media";
if ($ordine_scuola == 2){
	$school_order_directory = "scuola_primaria";
}

ini_set("display_errors", DISPLAY_ERRORS);
header("Content-type: text/plain");

$rb = RBUtilities::getInstance($db);

if ($_SESSION['__user__']->isSupplyTeacher()) {
	$tit = $_SESSION['__user__']->getLecturer();
	$user = $rb->loadUserFromUid($tit, 'school');
}
else {
	$user = $_SESSION['__user__'];
}

$user_directory = $user->getFullName();
$user_directory = preg_replace("/ /", "_", $user_directory);
$user_directory = strtolower($user_directory);
$path = $_SESSION['__path_to_root__']."download/registri/".$_SESSION['__current_year__']->get_descrizione()."/{$school_order_directory}/docenti/{$user_directory}/";
@mkdir($path, 0755, true);

$cls = $_POST['cls'];
$type = "standard";
if (isset($_POST['sub'])){
	$field = $_POST['sub'];
}
else if (isset($_POST['std'])){
	$field = $_POST['std'];
	$type = "support";
}

$log_manager = new TeacherRecordBookManager($user, $db, $path, $_SESSION['__current_year__'], $_SESSION['__school_year__'][$ordine_scuola], $type);
$log_manager->createRecordBook($cls, $field);

echo "ok";
