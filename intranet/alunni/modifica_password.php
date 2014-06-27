<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico - Area studenti";

if(!isset($_REQUEST['from']))
	$from = "";

include "modifica_password.html.php";

?>