<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

$navigation_label = "Area amministrazione: sviluppo";
$admin_level = 0;

include_once 'tests.html.php';
