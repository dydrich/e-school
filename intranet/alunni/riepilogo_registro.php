<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$student = $_SESSION['__user__']->getUid();

$navigation_label = "scuola secondaria";

include "../common/classbook_summary.php";

$drawer_label = $label;

include "riepilogo_registro.html.php";
