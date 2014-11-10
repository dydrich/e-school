<?php

require_once "../../lib/start.php";
require_once "../../lib/Widget.php";
require_once "../../lib/PageMenu.php";

ini_set("display_errors", "1");

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";
$page = "lezioni.php";
$area = "genitori";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$alunno = $_SESSION['__current_son__'];
$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$drawer_label = "Argomento delle lezioni";

require '../common/lessons.php';
