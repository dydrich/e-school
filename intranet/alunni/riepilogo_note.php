<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM|STD_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$area = "alunni";
$student_id = $_SESSION['__user__']->getUid();

$navigation_label = "scuola secondaria";
$drawer_label = "Note disciplinari";

include "../common/notes.php";
