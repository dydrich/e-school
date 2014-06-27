<?php

/**
    modifica la materia nella visualizazione del docente
    e nel database: il lato client usa Ajax per l'update
*/

require_once "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$id = $_POST['uid'];
$mat = $_POST['mat'];

header("Content-type: text/plain");

$upd = "UPDATE rb_docenti SET materia = $mat WHERE id_docente = $id";
$_SESSION['q'] = $upd;
try{
	$rs = $db->executeUpdate($upd);
} catch (MySQLException $ex){
    $ex->fake_alert();
    exit;
}
$_SESSION['q'] = $upd;

$sel_m = "SELECT materia FROM rb_materie WHERE id_materia = $mat";
$res_m = $db->executeQuery($sel_m);
$m = $res_m->fetch_assoc();
$res = "ok;doc_".$id.";".$m['materia'];

print $res;
exit;