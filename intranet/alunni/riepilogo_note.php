<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM|STD_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$area = "alunni";
$navigation_label = "Registro elettronico - Area alunni";
$student_id = $_SESSION['__user__']->getUid();

include "../common/notes.php";
