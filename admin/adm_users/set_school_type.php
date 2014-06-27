<?php

/**
    modifica la tipologia di scuola nella visualizzazione del docente
    e nel database: il lato client usa Ajax per l'update
*/

require_once "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

$id = $_POST['uid'];
$sc = $_POST['type'];
if(!is_numeric($sc)){
	echo "ko;Valore inserito non valido";
	exit;
}

header("Content-type: text/plain");

$upd = "UPDATE rb_docenti SET tipologia_scuola = {$sc} WHERE id_docente = {$id}";
try{
	$rs = $db->executeUpdate($upd);
} catch (MySQLException $ex){
    echo "kosql;".$ex->getQuery().";".$ex->getMessage();
    exit;
}
$_SESSION['q'] = $upd;

$sel_tipologie = "SELECT tipo FROM rb_tipologia_scuola WHERE id_tipo = {$sc}";
try{
	$tipo = $db->executeCount($sel_tipologie);
} catch (MySQLException $ex){
    echo "kosql;".$ex->getQuery().";".$ex->getMessage();
    exit;
}
$res = "ok;{$tipo}";

print $res;
exit;