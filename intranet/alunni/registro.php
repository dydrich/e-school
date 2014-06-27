<?php

require_once "../../lib/start.php";

ini_set("display_errors", "1");

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$area = "alunni";
$student_id = $_SESSION['__user__']->getUid();

$navigation_label = "Registro elettronico - Area studenti";

include "../common/classbook.php";

?>