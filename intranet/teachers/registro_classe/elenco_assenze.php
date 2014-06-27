<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM|GEN_PERM|STD_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$id_alunno = $_REQUEST['alunno'];

$assenze = array();
$assenze['09'] = array();
$assenze['10'] = array();
$assenze['11'] = array();
$assenze['12'] = array();
$assenze['01'] = array();
$assenze['02'] = array();
$assenze['03'] = array();
$assenze['04'] = array();
$assenze['05'] = array();
$assenze['06'] = array();
$sel_assenze = "SELECT data FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND data <= NOW() AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND id_alunno = $id_alunno ";
$res_assenze = $db->executeQuery($sel_assenze);
while($as = $res_assenze->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	array_push($assenze[$mese], $as['data']);
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $id_alunno";
$res_alunno = $db->executeQuery($sel_alunno);
$alunno = $res_alunno->fetch_assoc();
setlocale(LC_TIME, "it_IT");

include "elenco_assenze.html.php";

?>