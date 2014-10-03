<?php

require_once "../../lib/start.php";
require_once "../../lib/PageMenu.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

// estraggo le news
if(!isset($_GET['offset']))
    $offset = 0;
else
    $offset = $_GET['offset'];

$limit = 10;

$filter = "";
if (isset($_REQUEST['filter'])) {
	switch ($_REQUEST['filter']) {
		case "nome":
			$filter = "AND cognome LIKE '".$_REQUEST['nome']."%' ";
			break;
	}
}

$sel_user = "SELECT uid, nome, cognome, username, password FROM rb_utenti WHERE attivo = 1 {$filter} ORDER BY cognome, uid ";

if(!isset($_GET['second'])){
    $res_user = $db->execute($sel_user);
    //print $sel_links;
    $count = $res_user->num_rows;
    $_SESSION['count_users'] = $count;
}
else{
    $sel_user .= "LIMIT $limit OFFSET $offset";
    $res_user = $db->execute($sel_user);
}

if($offset == 0) {
    $page = 1;
}
else {
    $page = ($offset / $limit) + 1;
}
$pagine = ceil($_SESSION['count_users'] / $limit);
if($pagine < 1) {
    $pagine = 1;
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

$page_menu = new PageMenu("cmenu", "page_menu", "height: 80px; width: 150px; display: none", "div");
$html = '<p><a href="../../shared/no_js.php" id="filter_button" style="padding: 10px 0 0 5px; margin: 10px 0 0 0">&middot;&nbsp;&nbsp;&nbsp;Filtra elenco</a></p>';

$page_menu->setInnerHTML($html);
$page_menu->setJavascript('', 'jquery');
$page_menu->setPathToRoot($_SESSION['__path_to_root__']);
$page_menu->createLink();

$navigation_label = "Area amministrazione: gestione utenti";

include "users.html.php";
