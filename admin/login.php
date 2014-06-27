<?php

/*
 * form di accesso livello amministratore
 */

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__area_label__'] = "Area amministrazione";
$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

include "login.html.php";