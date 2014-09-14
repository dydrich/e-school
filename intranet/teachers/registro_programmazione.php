<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/09/14
 * Time: 16.58
 */

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "Registro elettronico - Programmazione";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];

$modulo = $_REQUEST['module'];
$_SESSION['fs_module'] = $modulo;

try {
	$res_mod = $db->executeQuery("SELECT * FROM rb_riunioni_programmazione WHERE id_modulo = $modulo ORDER BY data DESC");
} catch (MySQLException $ex) {
	$ex->redirect();
}
$riunioni = array();
while ($row = $res_mod->fetch_assoc()) {
	list ($y, $m, $d) = explode("-", $row['data']);
	$m = intval($m);
	if (!isset($riunioni[$m])) {
		$riunioni[$m] = array();
	}
	$riunioni[$m][] = $row;
}

setlocale(LC_TIME, "it_IT.utf8");

include "registro_programmazione.html.php";
