<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$back_link = "users.php";
if(basename($_SERVER['HTTP_REFERER']) == "genitori.php") {
	$back_link = "../adm_parents/genitori.php";
}
$offset = 0;
if (isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
	if($offset != 0){
		$back_link .= "?second=1&offset={$offset}";
	}
}


// estrazione gruppi
$sel_g = "SELECT gid, nome, codice FROM rb_gruppi WHERE gid != (SELECT gid from rb_gruppi WHERE nome = 'studenti') ORDER BY nome";
$res_g = $db->execute($sel_g);

if(isset($_GET['id']) && $_GET['id'] != 0){
    // modifica
    $sel_usr = "SELECT * FROM rb_utenti WHERE uid = ".$_GET['id'];
    $res_usr = $db->execute($sel_usr);
    $user = $res_usr->fetch_assoc();
    $_i = $_GET['id'];
	$sel_gr = "SELECT gid FROM rb_gruppi_utente WHERE uid = {$_GET['id']}";
	try {
		$groups = $db->executeQuery($sel_gr);
	} catch (MySQLException $sx) {
		print("ko;".$ex->getMessage().";".$ex->getQuery());
		exit;
	}
	$gid = array();
	while($group = $groups->fetch_assoc()) {
		$gid[] = $group['gid'];
	}
	$drawer_label = "Dettaglio utente";
}
else{
    // nuovo utente
	$drawer_label = "Nuovo utente";
    $_i = 0;
}

$navigation_label = "gestione utenti";

include "dettaglio_utente.html.php";
