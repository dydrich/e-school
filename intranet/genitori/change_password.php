<?php

require_once "../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

if(!isset($_REQUEST['from']))
	$from = "";

$navigation_label = "area privata";
$drawer_label = "Modifica password";

include "change_password.html.php";
