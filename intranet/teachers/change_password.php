<?php

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

if(!isset($_REQUEST['from']))
	$from = "";

$navigation_label = "registro elettronico";
$drawer_label = "Modifica password";

include "change_password.html.php";
