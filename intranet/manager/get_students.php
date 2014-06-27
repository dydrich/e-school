<?php

require_once "../../lib/start.php";

$param = $_REQUEST['term'];
$school = $_SESSION['__school_order__'];

$sel_users = "SELECT id_alunno AS uid, cognome, nome, CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS other, rb_alunni.id_classe FROM rb_alunni, rb_classi WHERE rb_classi.id_classe = rb_alunni.id_classe AND ordine_di_scuola = {$school} AND cognome LIKE '%{$param}%' AND attivo = '1' ORDER BY cognome, nome";
$res_users = $db->execute($sel_users);
$users = array();
while ($us = $res_users->fetch_assoc()){
	$name = $us['cognome']." ".$us['nome']." (".$us['other'].")";
	$users[] = array('uid' => $us['uid'], "value" => $name, "id_classe" => $us['id_classe'], "classe" => $us['other']);
}

$json_users = json_encode($users);
header("Content-type: text/plain");
echo $json_users;
exit;