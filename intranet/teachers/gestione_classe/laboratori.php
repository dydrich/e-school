<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 20/01/16
 * Time: 17.00
 */
require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

require_once "../reload_class_in_session.php";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();

$res_labs = $db->executeQuery("SELECT rb_aule_speciali.*, rb_sedi.nome AS sede FROM rb_aule_speciali, rb_sedi WHERE id_sede = rb_aule_speciali.sede AND ordine_di_scuola = {$ordine_scuola}");

$navigation_label = "gestione classe";
$drawer_label = "Elenco laboratori";

include "laboratori.html.php";
