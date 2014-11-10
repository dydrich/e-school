<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

$navigation_label = "scuola secondaria";
$area = "alunni";
$alunno = $_SESSION['__user__']->getUid();

$drawer_label = "Medie voto totali";

include "../common/grades.php";
