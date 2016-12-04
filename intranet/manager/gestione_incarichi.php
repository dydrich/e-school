<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 11/19/16
 * Time: 7:46 PM
 * gestione incarichi
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$drawer_label = "Elenco incarichi ";
$navigation_label = "";
switch($_SESSION['__school_order__']) {
	case 1:
		$navigation_label .= "scuola secondaria";
		break;
	case 2:
		$navigation_label .= "scuola primaria";
		break;
}

/*
 * ruoli
 */
$sel_roles = "SELECT * FROM rb_ruoli";
$res_roles = $db->executeQuery($sel_roles);

/*
 * utenti e ruoli
 */
$roles = [];
$sel = "SELECT cognome, nome, id, rid, rb_utenti.uid AS uid FROM rb_utenti, rb_ruoli_utente WHERE rb_utenti.uid = rb_ruoli_utente.uid ORDER BY rid, cognome, nome";
$res = $db->executeQuery($sel);
if($res->num_rows > 0) {
	while($row = $res->fetch_assoc()) {
		if (!isset($roles[$row['rid']])) {
			$roles[$row['rid']] = [];
		}
		$roles[$row['rid']][$row['uid']] = $row;
	}
}

include "gestione_incarichi.html.php";