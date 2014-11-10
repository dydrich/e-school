<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", "1");

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$area = "genitori";
$page = "registro.php";
$student_id = $_SESSION['__current_son__'];
$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$drawer_label = "Registro di classe";

include "../common/classbook.php";
