<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/25/16
 * Time: 3:15 PM
 */
require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$months = [
			0 => ['num' => 9, 'desc' => 'Settembre'],
			1 => ['num' => 10, 'desc' => 'Ottobre'],
			2 => ['num' => 11, 'desc' => 'Novembre'],
			3 => ['num' => 12, 'desc' => 'Dicembre'],
			4 => ['num' => 1, 'desc' => 'Gennaio'],
			5 => ['num' => 2, 'desc' => 'Febbraio'],
			6 => ['num' => 3, 'desc' => 'Marzo'],
			7 => ['num' => 4, 'desc' => 'Aprile'],
			8 => ['num' => 5, 'desc' => 'Maggio'],
			9 => ['num' => 6, 'desc' => 'Giugno']
];

$sel_coll = "SELECT id, data, MONTH(data) AS month FROM rb_colloqui_periodici WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND ordine_scuola = 1 ORDER BY data";
$res_coll = $db->executeQuery($sel_coll);
$colloqui = [];
if ($res_coll->num_rows > 0) {
	while($row = $res_coll->fetch_assoc()) {
		if (!isset($colloqui[$row['month']])) {
			$colloqui[$row['month']] = [];
		}
		$colloqui[$row['month']][] = $row;
	}
}

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);
$drawer_label = "Gestione colloqui periodici";

include "colloqui.html.php";