<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 09/05/15
 * Time: 20.06
 */
require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$admin_level = 1;

$id = $_REQUEST['id'];

$event = null;

if ($id != 0) {
	try {
		$sel_events = "SELECT * FROM rb_tipievento_log WHERE id = $id";
		$res_events = $db->execute($sel_events);
		$event = $res_events->fetch_assoc();
	} catch (MySQLException $ex) {
		$ex->redirect();
	}
}

$drawer_label = "Gestione eventi";
$navigation_label = "eventi tracciati";

include "event.html.php";
