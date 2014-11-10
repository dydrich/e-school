<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

if (isset($_SESSION['__user_config__']['riepilogo_registro'])) {
	$active = $_SESSION['__user_config__']['riepilogo_registro'][0];
}
else {
	$active = 0;
}

$navigation_label = "registro elettronico ";
$drawer_label = "Configurazione registro di classe";

include "conf_classbook.html.php";
