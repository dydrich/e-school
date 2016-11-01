<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";
require_once "../../lib/RBUtilities.php";
require_once "../../lib/ParentsMeetingsManager.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";
$page = "cdc.php";

$area = "genitori";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$utilities = RBUtilities::getInstance($db);
$data = $utilities->getTeachersOfClass($_SESSION['__classe__']->get_ID());

$meetings_manager = new \eschool\ParentsMeetingsManager($_SESSION['__school_order__'], new MySQLDataLoader($db));
setlocale(LC_ALL, "it_IT.utf8");

$schedule_module = $_SESSION['__classe__']->get_modulo_orario();

$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$drawer_label = "Elenco docenti del consiglio di classe ". $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "cdc.html.php";
