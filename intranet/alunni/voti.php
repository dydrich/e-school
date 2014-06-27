<?php

require_once "../../lib/start.php";

ini_set("display_errors", "1");

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}
	
$navigation_label = "Registro elettronico - Area studenti";
$area = "studenti";
$alunno = $_SESSION['__user__']->getUid();

include "../common/grades.php";

?>