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

$navigation_label = "Registro elettronico genitori: alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];

include "profile.html.php";
