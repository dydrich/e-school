<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$anno = $_SESSION['__current_year__']->get_ID();
$school = $_SESSION['__school_order__'];

$drawer_label = "Segnala alunno con sostegno";

include "segnala_alunno.html.php";
