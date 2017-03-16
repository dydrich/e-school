<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 3/12/17
 * Time: 9:40 AM
 */
require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$uniqid = uniqid('PRMD', false);
$user = $_SESSION['__user__']->getUid();

$db->executeUpdate("INSERT INTO rb_richieste_permessi (data, utente, codice) VALUES (NOW(), {$user}, '{$uniqid}')");

$response['message'] = 'Il codice della tua pratica è: '.$uniqid;

$res = json_encode($response);
echo $res;
exit;
