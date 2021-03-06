<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$year = $_REQUEST['y'];
$q = "0";
if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}

$sel_anni = "SELECT id_anno, descrizione FROM rb_anni WHERE id_anno IN (SELECT DISTINCT anno FROM rb_pubblicazione_pagelle)";
try{
	$res_anni = $db->executeQuery($sel_anni);
} catch(MySQLException $ex){
	$ex->redirect();
}

$sel_tipi = "SELECT id_tipo, tipo FROM rb_tipologia_scuola ORDER BY id_tipo";
try{
	$res_tipi = $db->executeQuery($sel_tipi);
} catch(MySQLException $ex){
	$ex->redirect();
}

$sel_classi = "SELECT id_classe, CONCAT(anno_corso, sezione) AS classe FROM rb_classi WHERE ordine_di_scuola = {$_SESSION['__school_order__']} ORDER BY sezione, anno_corso";
try{
	$res_classi = $db->executeQuery($sel_classi);
} catch(MySQLException $ex){
	$ex->redirect();
}

$drawer_label = "Ricerca pagella";

include "cerca_pagella.html.php";
