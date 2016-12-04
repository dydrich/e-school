<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 11/5/16
 * Time: 6:21 PM
 * prenotazioni effettuate
 */
require_once "../../../lib/start.php";
require_once "../../../lib/ParentsMeetingsManager.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$anno = $_SESSION['__current_year__']->get_ID();

$pmm = new \eschool\ParentsMeetingsManager($ordine_scuola, new MySQLDataLoader($db));

$drawer_label = "Prenotazioni colloqui";
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "registro personale";
setlocale(LC_TIME, "it_IT.utf8");

$data = $pmm->getNextTeacherReservation($_SESSION['__user__']->getUid(), 2);

include "colloqui.html.php";