<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/07/14
 * Time: 17.39
 */
require_once "../../lib/start.php";
require_once "../../lib/Substitution.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];
$year = $_SESSION['__current_year__']->get_ID();

$id = $_REQUEST['id'];
$label = "Nuova";
$action = "new";
if ($_REQUEST['id'] != 0) {
	$subs = \eschool\Substitution::getInstance($_REQUEST['id'], new MySQLDataLoader($db));
	$label = "Modifica";
	$action = "update";
}

$drawer_label = $label." supplenza";

$sel_classi = "SELECT rb_classi.id_classe, anno_corso, sezione FROM rb_classi WHERE ordine_di_scuola =  {$_SESSION['__school_order__']} ORDER BY sezione, anno_corso";
$res_classi = $db->execute($sel_classi);

include "supplenza.html.php";
