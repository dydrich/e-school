<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 03/11/15
 * Time: 17.57
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);
$drawer_label = "Gestione pagellini";

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$months = array("11" => "Novembre", "12" => "Dicembre", "1" => "Gennaio", "3" => "Marzo", "4" => "Aprile", "5" => "Maggio");

$sel_pagellini = "SELECT * FROM rb_pagellini WHERE anno_scolastico = {$_SESSION['__current_year__']->get_ID()} ORDER BY id_pagellino DESC";
$res_pagellini = $db->executeQuery($sel_pagellini);

$pagellini = array();
if ($res_pagellini) {
	while ($row = $res_pagellini->fetch_assoc()) {
		$pagellini[$row['id_pagellino']] = $row;
	}
}

include "pagellini.html.php";
