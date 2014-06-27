<?php

require_once "../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

if(!isset($_REQUEST['from']))
	$from = "";

$navigation_label = "Registro elettronico genitori: alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];

include "change_password.html.php";

?>