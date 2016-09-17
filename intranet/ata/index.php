<?php

require_once "../../lib/start.php";

check_session();
check_permission(ATA_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "ata";

$navigation_label = "";
$drawer_label = "Home page";

include "index.html.php";
