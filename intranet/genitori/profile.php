<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

// pagina da ricaricare in header.php
$page = "index.php";

include "check_sons.php";

$navigation_label = "area privata";
$drawer_label = "Profilo personale";

include "profile.html.php";
