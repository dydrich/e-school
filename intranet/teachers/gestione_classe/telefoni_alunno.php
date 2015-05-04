<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 02/05/15
 * Time: 23.42
 */
require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$stid = $_REQUEST['stid'];

$tel = array();

$sel_alunno = "SELECT CONCAT_WS(' ', cognome, nome) FROM rb_alunni WHERE id_alunno = $stid";
$alunno = $db->executeCount($sel_alunno);

$sel_phones = "SELECT * FROM rb_telefoni_alunni WHERE id_alunno = $stid ORDER BY principale DESC";
$res_phones = $db->execute($sel_phones);
while ($row = $res_phones->fetch_assoc()) {
	$tel[$row['id']] = $row;
}

$navigation_label = "gestione classe";
$drawer_label = "Recapiti telefonici di $alunno";

include "telefoni_alunno.html.php";
