<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$sel_pagelle = "SELECT * FROM rb_pubblicazione_pagelle ORDER BY anno DESC, quadrimestre";
try{
	$res_pagelle = $db->executeQuery($sel_pagelle);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}
$pagelle = array();
$field = array("data_pubblicazione", "ora_pubblicazione", "disponibili_docenti");
if ($_SESSION['__school_order__'] == 2){
	$field[0] = "data_pubblicazione_sp";
	$field[1] = "ora_pubblicazione_sp";
	$field[2] = "disponibili_docenti_sp";
}
while($pag = $res_pagelle->fetch_assoc()){
	if (!isset($pagelle[$pag['anno']])){
		$pagelle[$pag['anno']] = array();
	}
	if ($pag[$field[0]] != ""){
		$pagelle[$pag['anno']][$pag['quadrimestre']]['data_pubblicazione']  = $pag[$field[0]];
		$pagelle[$pag['anno']][$pag['quadrimestre']]['ora_pubblicazione']   = substr($pag[$field[1]], 0, 5);
		$pagelle[$pag['anno']][$pag['quadrimestre']]['disponibili_docenti'] = $pag[$field[2]];
	}
	else {
		$pagelle[$pag['anno']][$pag['quadrimestre']] = "";
	}
}

$drawer_label = "Pagelle online";

include "pagelle.html.php";
