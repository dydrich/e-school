<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$navigation_label = "sviluppo";
$drawer_label = "Test unit: managers";
$admin_level = 0;

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

include_once 'tests.html.php';
