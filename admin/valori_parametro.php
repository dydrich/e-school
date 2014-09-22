<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", "1");

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$year = $_SESSION['__current_year__']->get_ID();

$school_order = $_REQUEST['school'];

$admin_level = getAdminLevel($_SESSION['__user__']);

$sel_params = "SELECT * FROM rb_parametri_pagella WHERE id = {$_REQUEST['id']}";
$res_params = $db->execute($sel_params);
$param = $res_params->fetch_assoc();

$sel_g = "SELECT * FROM rb_giudizi_parametri_pagella WHERE id_parametro = {$_REQUEST['id']}";
$res_g = $db->execute($sel_g);

$navigation_label = "Area amministrazione: parametri pagella";

include "valori_parametro.html.php";
