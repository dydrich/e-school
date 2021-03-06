<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if(!isset($_REQUEST['cls'])){
	$_REQUEST['cls'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}

$idd = 0;
if (isset($_SESSION['__sp_student__']['dati']['id'])){
	$idd = $_SESSION['__sp_student__']['dati']['id'];
}

$navigation_label = "registro del sostegno ";
$drawer_label = "Profilo personale di ". $_SESSION['__sp_student__']['cognome']." ".$_SESSION['__sp_student__']['nome'];

include "profilo.html.php";
