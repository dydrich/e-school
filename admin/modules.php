<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$sel_modules = "SELECT id, name, depends_to, code_name, active, CASE type WHEN 1 THEN 'modulo' WHEN 2 THEN 'area' END AS tipo FROM rb_modules ORDER BY tipo";
$res_modules = $db->executeQuery($sel_modules);

$navigation_label = "Area amministrazione: sviluppo";

$admin_level = 0;

include "modules.html.php";