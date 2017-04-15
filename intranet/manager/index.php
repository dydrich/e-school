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
			$_SESSION['active_group'] = ['perms' => SEG_PERM, 'desc' => 'segreteria'];
			$_SESSION['wflow_office'] = 1;
			break;
		case 6:
			$_SESSION['__role__'] = "Dirigente scolastico";
			$_SESSION['__administration_group__'] = "menu_ds";
			$_SESSION['area_from']['area'] = "DS";
			$_SESSION['active_group'] = ['perms' => DIR_PERM, 'desc' => 'dirigenza'];
			$_SESSION['wflow_office'] = 2;
			break;
		case 7:
			$_SESSION['__role__'] = "DSGA";
			$_SESSION['__administration_group__'] = "menu_dsga";
			$_SESSION['area_from']['area'] = "DSGA";
			$_SESSION['active_group'] = ['perms' => DSG_PERM, 'desc' => 'dsga'];
			$_SESSION['wflow_office'] = 3;
			break;
	}
}
$_SESSION['area_from']['menu'] = "../../intranet/manager/".$_SESSION['__administration_group__']."/menu.php";

$navigation_label = "";
$drawer_label = "Home page";

include "index.html.php";
