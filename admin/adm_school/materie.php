<?php

require_once "../../lib/start.php";
require_once "../../lib/Widget.php";
require_once "../../lib/PageMenu.php";

check_session();
check_permission(ADM_PERM|AIS_PERM|AMS_PERM|APS_PERM);

$admin_level = 0;

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

if(!isset($_REQUEST['offset'])) {
	$offset = 0;
}
else {
	$offset = $_REQUEST['offset'];
}

$limit = 11;

$params = "";
if($_SESSION['__school_order__'] != 0){
	$params = "AND tipologia_scuola = {$_SESSION['__school_order__']}";
}
else if(isset($_REQUEST['sc']) && is_numeric($_REQUEST['sc'])){
	$params = "AND tipologia_scuola = {$_REQUEST['sc']}";
}

$sel_materie = "SELECT rb_materie.* FROM rb_materie, rb_tipologia_scuola WHERE tipologia_scuola = id_tipo AND attivo = 1 AND has_admin = 1 AND id_materia <> 1 {$params} ORDER BY idpadre, materia";
try {
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex) {
	$ex->redirect();
}
$subjects = array();
/* 
 * carico le materie base e i figli
 */
while($materia = $res_materie->fetch_assoc()) {
	$m = new Subject($materia);
	if($materia['has_sons']){
		try {
			$sons = $db->executeQuery("SELECT * FROM rb_materie WHERE idpadre = ".$materia['id_materia']);
		} catch (MySQLException $ex) {
			$ex->redirect();
		}
		while ($_materia = $sons->fetch_assoc()){
			$m->addChildren(new Subject($_materia));
		}
	}
	$subjects[$materia['id_materia']] = $m;
}
/*
 * carico il parent
 */
$res_materie->data_seek(0);
while($materia = $res_materie->fetch_assoc()) {
	if($materia['idpadre'] != "") {
		$subjects[$materia['id_materia']]->setParent($subjects[$materia['idpadre']]);
	}
}

$_SESSION['count_materie'] = count($subjects);
$pagine = ceil($_SESSION['count_materie'] / $limit);
if($pagine < 1) {
	$pagine = 1;
}

// tipologie di scuola
$sel_tipologie = "SELECT * FROM rb_tipologia_scuola WHERE has_admin = 1 AND attivo = 1";
$res_tipologie = $db->executeQuery($sel_tipologie);
$tipologie = array();
while($tipo = $res_tipologie->fetch_assoc()){
	$tipologie[$tipo['id_tipo']] = array("id" => $tipo['id_tipo'], "desc" => substr($tipo['tipo'], 6), "code" => $tipo['codice']);
}

/*
 * PageMenu widget
*/
if($_SESSION['__school_order__'] == 0){
	$page_menu = new PageMenu("cmenu", "page_menu", "height: 150px; width: 180px; display: none", "div");
	$page_menu->setDatasource($tipologie);
	$page_menu->setJavascript('', 'jquery');
	$page_menu->createInnerHTML();
	$page_menu->setPathToRoot($_SESSION['__path_to_root__']);
	$page_menu->createLink();
}

// dati per la paginazione (navigate.php)
$colspan = 3;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_materie";
$row_class = "admin_void";
if(isset($_REQUEST['sc']) && is_numeric($_REQUEST['sc'])){
	$nav_params = "&sc=".$_REQUEST['sc'];
}

$navigation_label = "Area amministrazione: gestione materie";

include "materie.html.php";
