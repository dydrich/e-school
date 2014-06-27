<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$admin_level = getAdminLevel($_SESSION['__user__']);

$year = $_SESSION['__current_year__']->get_ID();

$cls = array();
$sel_classi = "SELECT * FROM rb_classi ORDER BY sezione, anno_corso";
$res_classi = $db->execute($sel_classi);
while($c = $res_classi->fetch_assoc()){
	$cls[$c['id_classe']] = new Classe($c, $db);
}

$navigation_label = "Area amministrazione: gestione nuovo anno";

include "schedule_table.html.php";