<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$admin_level = getAdminLevel($_SESSION['__user__']);

$year = $_SESSION['__current_year__']->get_ID();

$school_order = $_REQUEST['school'];

$sel_params = "SELECT * FROM rb_parametri_pagella WHERE id = {$_REQUEST['id']}";
$res_params = $db->execute($sel_params);
$param = $res_params->fetch_assoc();

$navigation_label = "Area amministrazione: gestione pagelle";

include "dettaglio_parametro.html.php";