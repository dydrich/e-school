<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";
$page = "elenco_note_didattiche.php";
$area = "genitori";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$student_id = $_SESSION['__current_son__'];
$student = $stds[$_SESSION['__current_son__']][0];

$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];

include "../common/didactic_notes.php";
