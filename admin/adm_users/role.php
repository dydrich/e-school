<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/3/16
 * Time: 5:30 PM
 * incarico
 */
require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

if (isset($_REQUEST['rid']) && $_REQUEST['rid'] != 0) {
	$role = [];
	$rl = $db->executeQuery("SELECT * FROM rb_ruoli WHERE rid = {$_REQUEST['rid']}");
	if($rl->num_rows > 0) {
		$row = $rl->fetch_assoc();
		$role['rid'] = $row['rid'];
		$role['name'] = $row['nome'];
		$role['perms'] = $row['permessi'];
	}
	$drawer_label = "Dettaglio incarico ";
}
else {
	$role = null;
	$drawer_label = "Nuovo incarico ";
}

$navigation_label = "gestione incarichi";

include "role.html.php";
