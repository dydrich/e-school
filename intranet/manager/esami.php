<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 1/17/17
 * Time: 6:12 PM
 * gestione esami di licenza
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Esami di Stato a. s. ".$_SESSION['__current_year__']->get_descrizione();

include "esami.html.php";