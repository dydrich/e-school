<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 02/05/15
 * Time: 11.05
 */
require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$admin_level = 1;

$filter = "";

if(isset($_REQUEST['area'])) {
	$filter = " AND numeric1 = ".$_REQUEST['area'];
}

$sel_log = "SELECT * FROM rb_log WHERE tipo_evento = 2 $filter AND data_ora >= '".$_SESSION['__current_year__']->get_data_apertura()."' ORDER BY data_ora DESC";
$res_log = $db->execute($sel_log);

$drawer_label = "Login non riusciti (estratti ".$res_log->num_rows." record)";
$navigation_label = "analisi log";

include "login_failed.html.php";
