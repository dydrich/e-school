<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 3/11/17
 * Time: 10:11 PM
 */
require_once "../../lib/start.php";

ini_set('display_errors', 1);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$user = $_SESSION['__user__']->getUid();

$sel_perms = "SELECT * FROM rb_richieste_permessi WHERE utente = {$user} ORDER BY data DESC";
$res_perms = $db->executeQuery($sel_perms);

$navigation_label = "registro elettronico ";
$drawer_label = "Richieste di permesso";

include "richiesta_permesso.html.php";