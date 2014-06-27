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

$sel_params = "SELECT * FROM rb_parametri_pagella WHERE ordine_scuola = {$school_order}";
$res_params = $db->execute($sel_params);

$navigation_label = "Area amministrazione: gestione tabella scrutini";

include "parametri_pagella.html.php";