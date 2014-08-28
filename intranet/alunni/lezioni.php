<?php

require_once "../../lib/start.php";
require_once "../../lib/Widget.php";
require_once "../../lib/PageMenu.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$alunno = $_SESSION['__user__']->getUid();
$navigation_label = "Registro elettronico - Area studenti";

require '../common/lessons.php';
