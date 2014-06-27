<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";
$page = "cdc.php";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$classe = $_SESSION['__classe__']->get_ID();
	
$sel_cdc = "SELECT cognome, nome, materia FROM rb_utenti, rb_materie, rb_cdc WHERE rb_cdc.id_docente = rb_utenti.uid AND rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_anno = ".$_SESSION['__current_year__']->get_ID()." AND rb_cdc.id_classe = ".$classe;
$res_cdc = $db->execute($sel_cdc);
$cdc = array();
while($_cdc = $res_cdc->fetch_assoc()){
	array_push($cdc, array($_cdc['cognome']." ".$_cdc['nome'], $_cdc['materia']));
}

$navigation_label = "Registro elettronico genitori: alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];

include "cdc.html.php";

?>