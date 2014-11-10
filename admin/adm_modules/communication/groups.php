<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/08/14
 * Time: 19.27
 */
require_once "../../../modules/communication/lib/Thread.php";
require_once "../../../lib/start.php";
require_once "../../../lib/RBUtilities.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../../";

$rb = RBUtilities::getInstance($db);

if(!isset($_GET['offset'])) {
	$offset = 0;
}
else {
	$offset = $_GET['offset'];
}

$limit = 20;

if (!isset($_SESSION['threads'])) {
	$sel_th = "SELECT rb_com_threads.* FROM rb_com_threads WHERE type = 'G' ORDER BY name";
	try {
		$res_th = $db->executeQuery($sel_th);
	} catch (MySQLException $ex) {
		$ex->redirect();
	}

	$threads = array();
	while ($th = $res_th->fetch_assoc()){
		if ($th['owner'] != "") {
			$owner = $rb->loadUserFromUniqID($th['owner']);
			//$other_user = $u2;
		}
		else {
			$owner = "";
		}
		$res_users = $db->executeQuery("SELECT utente FROM rb_com_utenti_thread WHERE thread = {$th['tid']}");
		$users = array();
		while ($row = $res_users->fetch_assoc()) {
			$users[] = $row['utente'];
		}
		$thread = new Thread($th['tid'], new MySQLDataLoader($db), $th['creation']);
		if ($th['type'] == 'G') {
			$thread->setName($th['name']);
			$thread->setType('G');
		}
		$thread->setUsers($users);
		$threads[$th['tid']] = $thread;
	}
	$_SESSION['threads'] = $threads;
	$_SESSION['count_groups'] = count($threads);
}
else {
	$threads = $_SESSION['threads'];
}

if($offset == 0) {
	$page = 1;
}
else {
	$page = ($offset / $limit) + 1;
}
$pagine = ceil($_SESSION['count_groups'] / $limit);
if($pagine < 1) {
	$pagine = 1;
}

$array = array_chunk($threads, 20);

// dati per la paginazione (navigate.php)
$colspan = 3;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_groups";
$row_class = "admin_void";
$row_class_menu = " admin_row_menu";

$navigation_label = "gestione moduli";
$drawer_label = "Modulo communication: elenco gruppi, pagina $page di $pagine";

include "groups.html.php";
