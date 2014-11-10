<?php

require_once "../../lib/start.php";
require_once "../../lib/ScheduleModule.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = getAdminLevel($_SESSION['__user__']);

$offset = 0;
if(isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}

$level = 0;
if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$level = $_SESSION['__school_order__'];
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$level = $_GET['__school_order__'];
}
else if (isset($_GET['__school_order__']) && $_GET['__school_order__'] != 0){
	$level = $_GET['__school_order__'];
}
$param_school = '';
if ($level != 0){
	$param_school = "AND id_tipo = {$level}";
}

$sel_ordini = "SELECT * FROM rb_tipologia_scuola WHERE has_admin = 1 AND attivo = 1 {$param_school} ORDER BY id_tipo DESC";
$res_ordini = $db->execute($sel_ordini);

$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE rb_classi.id_classe = {$_REQUEST['id']} AND sede = id_sede";
$sel_sedi = "SELECT * FROM rb_sedi ";
$sel_modules = "SELECT * FROM rb_moduli_orario ORDER BY id_modulo";
try{
	if($_REQUEST['id'] != 0){
		$res_classe = $db->executeQuery($sel_classe);
	}
	$res_sedi = $db->executeQuery($sel_sedi);
	$res_modules = $db->executeQuery($sel_modules);
} catch (MySQLException $ex){
	$ex->redirect();
}
if($_REQUEST['id'] != 0){
	$_cls = $res_classe->fetch_assoc();
	$cls = new Classe($_cls, $db);
}

$sezioni = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$action = "";
if ($_REQUEST['id'] == 0) {
	$action = "insert";
	$drawer_label = "Nuova classe";
}
else {
	$action = "update";
	$drawer_label = "Gestione classe: ".$cls->get_anno().$cls->get_sezione()." - ".$_cls['nome'];
}

$navigation_label = "gestione classi";

include "classe.html.php";
