<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";

$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$student = $_SESSION['__current_son__'];
$page = "riepilogo_registro.php";

include "../common/classbook_summary.php";

$drawer_label = $label;

include "riepilogo_registro.html.php";
