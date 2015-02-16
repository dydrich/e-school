<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 16/02/15
 * Time: 11.27
 * update username (and password?)
 */
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

if(isset($_GET['uid'])){
	$sel_usr = "SELECT * FROM rb_utenti WHERE uid = ".$_GET['uid'];
	$res_usr = $db->execute($sel_usr);
	$user = $res_usr->fetch_assoc();
	$drawer_label = "Modifica account";
}

$navigation_label = "gestione utenti";

include "modifica_account.html.php";
