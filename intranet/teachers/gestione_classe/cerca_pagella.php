<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

ini_set("display_errors", "1");

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$navigation_label = "Registro elettronico: area docenti";

$school = $_SESSION['__user__']->getSchoolOrder();
$_SESSION['__school_order__'] = $school;

$year = $_SESSION['__current_year__']->get_ID();
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

$sel_classi = "SELECT id_classe, CONCAT(anno_corso, sezione) AS classe FROM rb_classi WHERE ordine_di_scuola = {$school} ORDER BY sezione, anno_corso";
try{
	$res_classi = $db->executeQuery($sel_classi);
} catch(MySQLException $ex){
	$ex->redirect();
}

$_SESSION['no_file'] = array("referer" => "intranet/teachers/gestione_classe/cerca_pagella.php", "path" => "intranet/teachers/", "relative" => "gestione_classe/cerca_pagella.php");

include "cerca_pagella.html.php";

?>