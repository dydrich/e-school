<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 12/3/16
 * Time: 4:54 PM
 * gestione incarichi
 */
require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$roles = [];
$sel_roles = "SELECT nome, permessi, rid FROM rb_ruoli ORDER BY rid";
$sel = "SELECT cognome, nome, id, rid, rb_utenti.uid AS uid FROM rb_utenti, rb_ruoli_utente WHERE rb_utenti.uid = rb_ruoli_utente.uid ORDER BY rid, cognome, nome";
try {
	$res_roles = $db->executeQuery($sel_roles);
	$res = $db->executeQuery($sel);
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = "Si è verificato un errore";
	$response['dbg_message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	$res = json_encode($response);
	echo $res;
	exit;
}

if($res->num_rows > 0) {
	while($row = $res->fetch_assoc()) {
		if (!isset($roles[$row['rid']])) {
			$roles[$row['rid']] = [];
		}
		$roles[$row['rid']][$row['uid']] = $row;
	}
}

$navigation_label = "gestione incarichi";
$drawer_label = "Elenco incarichi ";

include "roles.html.php";