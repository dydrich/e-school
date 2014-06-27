<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

header("Content-type: application/json");

$year = $_REQUEST['y'];

$sel_pubb = "SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$year}";
if($_POST['q'] != 0){
	$sel_pubb .= " AND quadrimestre = {$_POST['q']}";
}
$pubb = array();
try{
	$res_pubb = $db->executeQuery($sel_pubb);
} catch (MySQLException $ex){
	echo "kosql;".$ex->getMessage().";".$ex->getQuery();
	exit;
}
while($p = $res_pubb->fetch_assoc()){
	$pubb[] = $p['id_pagella'];
}
$string_pubb = join(",", $pubb);

$sel_stds = "SELECT cognome, nome, rb_alunni.id_alunno AS alunno, esito, id_file, id_pagella FROM rb_alunni, rb_pagelle WHERE rb_alunni.id_alunno = rb_pagelle.id_alunno AND id_pubblicazione IN ({$string_pubb}) ";
if ($_POST['ord'] != 0){
	$sel_stds .= " AND ";
}
if($_POST['lname'] != ""){
	$sel_stds .= " AND cognome LIKE '{$_POST['lname']}%'";
}
if($_POST['cls'] != 0){
	$sel_stds .= " AND rb_alunni.id_classe = {$_POST['cls']}";
}
$sel_stds .= " ORDER BY cognome, nome, id_file DESC";
try{
	$res_stds = $db->executeQuery($sel_stds);
} catch (MySQLException $ex){
	echo "kosql;".$ex->getMessage().";".$ex->getQuery();
	exit;
}

$json = array();
while($std = $res_stds->fetch_assoc()){
	$json[$std['alunno']] = array("nome" => $std['cognome']." ".$std['nome'], "esito" => $std['esito'], "id_file" => $std['id_file'], "id_pagella" => $std['id_pagella']);
}
echo json_encode($json);
exit;

?>