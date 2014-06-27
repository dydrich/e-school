<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 30/12/13
 * Time: 18.52
 */

require_once './lib/start.php';
require_once './lib/RBUtilities.php';
require_once './lib/AccountManager.php';

require_once "lib/SchoolYear.php";
$_SESSION['__path_to_root__'] = "/";

if(!isset($_SESSION['__config__'])){
	include_once "lib/load_env.php";
}

$token = $_GET['token'];
$today = date("Y-m-d H:i:s");
$area = $_GET['area'];
$due = false;

$sel_token = "SELECT data_scadenza_token, utente FROM rb_recupero_password WHERE token = '{$token}'";
$res_token = $db->executeQuery($sel_token);
$token = $res_token->fetch_assoc();
if ($token['data_scadenza_token'] < $today){
	$due = true;
	$token = null;
	$area = 0;
	$uid = 0;
}
else {
	$uid = $token['utente'];
}

include "change_password.html.php";

