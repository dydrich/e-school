<?php

/**
    modifica il flag ruolo nella visualizazione del docente
    e nel database: il lato client usa Ajax per l'update
*/

require_once "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$id = $_POST['uid'];

$sel_ruolo = "SELECT ruolo FROM rb_docenti WHERE id_docente = $id";
$res_ruolo = $db->executeQuery($sel_ruolo);
$usr = $res_ruolo->fetch_assoc();
$updated_role = "";
if($usr['ruolo'] == "S"){
    $upd = "UPDATE rb_docenti SET ruolo = 'N' WHERE id_docente = $id";
    $updated_role = "NO";
}
else{
    $upd = "UPDATE rb_docenti SET ruolo = 'S' WHERE id_docente = $id";
    $updated_role = "SI";
}
try{
	$rs = $db->executeUpdate($upd);
} catch (MySQLException $ex){
	$ex->alert();
	exit;
}

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata: $sel_ruolo");
$response['value'] = $updated_role;
echo json_encode($response);
exit;
