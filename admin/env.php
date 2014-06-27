<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$sel_env = "SELECT * FROM rb_config";
$res_env = $db->executeQuery($sel_env);

$navigation_label = "Area amministrazione: sviluppo";
$admin_level = 0;

include_once "env.html.php";