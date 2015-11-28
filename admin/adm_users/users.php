<?php

require_once "../../lib/start.php";
require_once "../../lib/PageMenu.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$filter = "";
if (isset($_REQUEST['filter'])) {
	switch ($_REQUEST['filter']) {
		case "nome":
			$filter = "AND cognome LIKE '".$_REQUEST['nome']."%' ";
			break;
	}
}

$sel_user = "SELECT uid, nome, cognome, username, password FROM rb_utenti WHERE attivo = 1 {$filter} ORDER BY cognome, uid ";

try{
    $res_user = $db->execute($sel_user);
    //print $sel_links;
    $count = $res_user->num_rows;
    $_SESSION['count_users'] = $count;
} catch (MySQLException $ex){
    $ex->redirect();
}

// dati per la paginazione (navigate.php)
$colspan = 3;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_users";
$row_class = "admin_void";
$row_class_menu = " admin_row_menu";
//print $_SESSION['q'];

/*
 * procedura guidata prima installazione
* first install wizard
*/
$goback = "Torna al menu";
$goback_link = "../index.php";
if(basename($_SERVER['HTTP_REFERER']) == "wiz_first_install.php?step=2"){
	$goback = "Torna al wizard";
	$goback_link = "../wiz_first_install.php?step=2";
}

$navigation_label = "gestione utenti";
$drawer_label = "Elenco utenti";

include "users.html.php";
