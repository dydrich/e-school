<?php

require_once "../../lib/start.php";

check_session();
check_permission(ATA_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "profilo personale";

$drawer_label = "Profilo personale";

include "profile.html.php";
