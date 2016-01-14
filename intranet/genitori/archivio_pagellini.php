<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 04/01/16
 * Time: 14.43
 * archivio schede di segnalazione insufficienze
 */
require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";
require_once "../../lib/StudentActivityReport.php";
require_once "../../lib/RBUtilities.php";
require_once "../../lib/ClassbookData.php";
require_once "../../lib/RBTime.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

// pagina da ricaricare in header.php
$page = "index.php";

include "check_sons.php";

if (count($_SESSION['__sons__'])  < 1) {
	include "no_sons.php";
	exit;
}

$utils = SessionUtils::getInstance($db);
$utils->registerCurrentClassFromUser($_SESSION['__current_son__'], "__classe__");

$rb = RBUtilities::getInstance($db);
$student = $rb->loadUserFromUid($_SESSION['__current_son__'], "student");

$res_rep = $db->executeQuery("SELECT * FROM rb_pagellini WHERE anno_scolastico = ".$_SESSION['__current_year__']->get_ID()." ORDER BY id_pagellino DESC");
$reports = [];
if ($res_rep->num_rows > 0) {
	while ($row = $res_rep->fetch_assoc()) {
		$reports[$row['id_pagellino']] = $row;
		$reports[$row['id_pagellino']]['subjects'] = [];
		$res_subjs = $db->executeQuery("SELECT rb_materie.materia AS mat FROM rb_materie, rb_segnalazioni_pagellino WHERE id_pagellino = {$row['id_pagellino']} AND id_materia = rb_segnalazioni_pagellino.materia AND alunno = {$_SESSION['__current_son__']}");
		if ($res_subjs->num_rows > 0) {
			while ($r = $res_subjs->fetch_assoc()) {
				$reports[$row['id_pagellino']]['subjects'][] = $r['mat'];
			}
		}
	}
}

$months = ["11" => "Novembre", "12" => "Dicembre", "1" => "Gennaio", "3" => "Marzo", "4" => "Aprile", "5" => "Maggio"];
$navigation_label = "alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];
$drawer_label = "Segnalazioni di insufficienze";

include "archivio_pagellini.html.php";
