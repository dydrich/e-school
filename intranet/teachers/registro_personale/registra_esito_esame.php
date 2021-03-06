<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 23/06/15
 * Time: 10.33
 */
require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$alunno = $_POST['alunno'];
$esito = $_POST['esito'];
$id_esito = $_POST['id_esito'];

if ($id_esito == 0) {
	// insert
	$sql = "INSERT INTO rb_esami_licenza (anno, classe, alunno, esito) VALUES ({$_SESSION['__current_year__']->get_ID()}, {$_SESSION['__classe__']->get_ID()}, {$alunno}, {$esito})";
}
else {
	// update
	$sql = "UPDATE rb_esami_licenza SET esito = $esito WHERE id = $id_esito";
}
try{
	$res = $db->executeUpdate($sql);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$response['id'] = $res;
echo json_encode($response);
exit;
