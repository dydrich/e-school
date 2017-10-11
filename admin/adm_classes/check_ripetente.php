<?php

/*
 * aggiorna il flag ripetente nell'alunno
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$rip = ($_REQUEST['checked'] == "true") ? 1 : 0;
$alunno = $_REQUEST['alunno'];

$upd = "UPDATE rb_alunni SET ripetente = $rip WHERE id_alunno = $alunno";
try{
	$db->executeUpdate($upd);
} catch (MySQLException $ex){
    $db->executeUpdate("ROLLBACK");
    $response['status'] = "kosql";
    $response['message'] = $ex->getMessage();
    $response['query'] = $ex->getQuery();
    echo json_encode($response);
    exit;
}

$_SESSION['__new_classes_step__'] = 1;
echo json_encode($response);
exit;