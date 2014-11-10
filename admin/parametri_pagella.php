<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", "1");

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$admin_level = getAdminLevel($_SESSION['__user__']);

$year = $_SESSION['__current_year__']->get_ID();

$school_order = $_REQUEST['school_order'];

$sel_params = "SELECT rb_parametri_pagella.*, COUNT(id_parametro) as count FROM rb_parametri_pagella, rb_giudizi_parametri_pagella WHERE rb_parametri_pagella.id = id_parametro AND ordine_scuola = {$school_order} GROUP BY rb_parametri_pagella.id, nome";
$res_params = $db->execute($sel_params);

$navigation_label = "gestione scrutini";
$drawer_label = "Elenco parametri pagella";

include "parametri_pagella.html.php";
