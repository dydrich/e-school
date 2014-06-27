<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

if (isset($_GET['role'])){
	switch ($_GET['role']){
		case 5:
			$_SESSION['__role__'] = "Segreteria";
			$_SESSION['__administration_group__'] = "menu_ata";
			break;
		case 6:
			$_SESSION['__role__'] = "Dirigente scolastico";
			$_SESSION['__administration_group__'] = "menu_ds";
			break;
		case 7:
			$_SESSION['__role__'] = "DSGA";
			$_SESSION['__administration_group__'] = "menu_dsga";
			break;
	}
	
}

$navigation_label = "Registro elettronico: area ".$_SESSION['__role__'];

include "index.html.php";

?>