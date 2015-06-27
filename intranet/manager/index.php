<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

/*
 * accesso all'area amministrazione da segreteria
 */
$_SESSION['area_from'] = array();

if (isset($_GET['role'])){
	switch ($_GET['role']){
		case 5:
			$_SESSION['__role__'] = "Segreteria";
			$_SESSION['__administration_group__'] = "menu_ata";
			$_SESSION['area_from']['area'] = "SEG";
			break;
		case 6:
			$_SESSION['__role__'] = "Dirigente scolastico";
			$_SESSION['__administration_group__'] = "menu_ds";
			$_SESSION['area_from']['area'] = "DS";
			break;
		case 7:
			$_SESSION['__role__'] = "DSGA";
			$_SESSION['__administration_group__'] = "menu_dsga";
			$_SESSION['area_from']['area'] = "DSGA";
			break;
	}
}
$_SESSION['area_from']['menu'] = "../../intranet/manager/".$_SESSION['__administration_group__']."/menu.php";

$navigation_label = "";
$drawer_label = "Home page";

include "index.html.php";
