<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "profilo personale";

if(!isset($_REQUEST['from']))
	$from = "";

$drawer_label = "Modifica password";

include "change_password.html.php";
