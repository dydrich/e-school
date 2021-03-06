<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$area = "alunni";
$student_id = $_SESSION['__user__']->getUid();

$navigation_label = "scuola secondaria";
$drawer_label = "Registro di classe, ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "../common/classbook.php";
