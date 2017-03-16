<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/04/15
 * Time: 18.13
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$year_desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID());

$drawer_label = "Stampa assenze";

include "stampa_assenze.html.php";
