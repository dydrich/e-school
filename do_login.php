<?php

require_once "lib/start.php";
require_once "lib/Authenticator.php";
require_once "lib/EventLogFactory.php";

/*
 * controllo della presenza di $_SESSION['__current_year__'] (bug #93)
 */
if(!isset($_SESSION['__current_year__'])){
	header("Location: index.php?auto=1");
	exit;
}

$_SESSION['__path_to_root__'] = "/";
//header("Content-type: text/plain");
header("Content-type: application/json");
$response = array();

$nick = $db->real_escape_string($_POST['nick']);
$pass = $db->real_escape_string($_POST['pass']);
$area = $_POST['param'];

$authenticator = new Authenticator(new MySQLDataLoader($db));
try {
	$user = $authenticator->login($area, $nick, $pass);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['query'] = $ex->getQuery();
	$response['message'] = $ex->getMessage();
	echo json_encode($response);
	exit;
} catch (Exception $e){
	$response['status'] = "ko";
	$response['message'] = $e->getMessage();
	echo json_encode($response);
	exit;
}

if ($user == null){
	$data = array();
	$data['username'] = $nick;
	$data['area'] = $area;
	$elf = \eschool\EventLogFactory::getInstance($data, new MySQLDataLoader($db));
	$log = $elf->getEventLog();
	$log->logFailedLogin();
	$response['status'] = "ko";
	$response['message'] = "Login non riuscito";
	echo json_encode($response);
	exit;
}

$_SESSION['__user__'] = $user;

$user_type = "";
if ($_SESSION['__user__'] instanceof SchoolUserBean){
	$user_type = "school";
}
else if ($_SESSION['__user__'] instanceof ParentBean){
	$user_type = "parent";
}
else {
	$user_type = "student";
}
$_SESSION['user_type'] = $user_type;
if (is_installed("com")) {
	$uniqID = $db->executeCount("SELECT id FROM rb_com_users WHERE uid = {$user->getUid()} AND type = '{$user_type}'");
	$_SESSION['__user__']->setUniqID($uniqID);
}

/*
 * tema grafico
 */
$id_theme = $db->executeCount("SELECT valore FROM rb_configurazioni_utente, rb_configurazioni WHERE nome = 'tema' AND id_configurazione = configurazione AND utente = {$user->getUid()}");
if ($id_theme != "" && $id_theme != null) {
	$_SESSION['__user_theme__'] = $db->executeCount("SELECT directory FROM rb_themes WHERE id_tema = {$id_theme}");
}

//echo $authenticator->getStringAjax();
$response = $authenticator->getResponse();
echo json_encode($response);
exit;
