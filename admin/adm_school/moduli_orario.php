<?php

require_once "../../lib/start.php";
require_once "../../lib/ScheduleModule.php";

check_session();
check_permission(ADM_PERM);

$admin_level = 0;
$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$sel_modules = "SELECT * FROM rb_moduli_orario ORDER BY id_modulo";
$res_modules = $db->execute($sel_modules);
$modules = array();
$data = array();
while($module = $res_modules->fetch_assoc()){
	$modules[$module['id_modulo']] = new ScheduleModule($db, $module['id_modulo']);
	$data[$module['id_modulo']] = $module;
}

$navigation_label = "Area amministrazione: gestione moduli orario";

include "moduli_orario.html.php";
