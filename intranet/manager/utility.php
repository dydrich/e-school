<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 06/07/14
 * Time: 20.59
 */

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$year_desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID());

$drawer_label = "Funzioni riservate";

include "utility.html.php";
