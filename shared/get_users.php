<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/07/14
 * Time: 18.51
 *
 * return users form autocompletion
 */
require_once "../lib/start.php";
require_once "../lib/MimeType.php";

ini_set("display_errors", DISPLAY_ERRORS);

$param = $_REQUEST['term'];
$add_param = "";
if (isset($_REQUEST['supp'])) {
	$add_param .= "AND ruolo = '{$_REQUEST['supp']}' ";
}

switch ($_REQUEST['group']){
	case "teachers":
		$sel = "SELECT uid, cognome, nome, '' AS other FROM rb_utenti, rb_docenti WHERE id_docente = uid AND tipologia_scuola = {$_REQUEST['ord']} ".$add_param." AND cognome LIKE '{$param}%' ORDER BY cognome, nome";
		break;
	default:
		break;
}
$res_users = $db->execute($sel);
$users = array();
while ($us = $res_users->fetch_assoc()){
	$name = $us['cognome']." ".$us['nome'];
	$users[] = array('uid' => $us['uid'], "value" => $name);
}

$json_users = json_encode($users);
header("Content-type: application/json");
echo $json_users;
exit;
