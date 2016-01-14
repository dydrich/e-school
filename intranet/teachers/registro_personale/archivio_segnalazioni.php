<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 06/01/16
 * Time: 22.42
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$anno = $_SESSION['__current_year__']->get_ID();

$months = ["11" => "Novembre", "12" => "Dicembre", "1" => "Gennaio", "3" => "Marzo", "4" => "Aprile", "5" => "Maggio"];
$sel_active = "SELECT * FROM rb_pagellini WHERE anno_scolastico = {$anno} AND data_chiusura < NOW() ORDER BY id_pagellino DESC";
$res_active = $db->executeQuery($sel_active);
$active = [];
if ($res_active->num_rows > 0) {
	while($row = $res_active->fetch_assoc()) {
		$active[] = $row;
	}
}

$mat = $_SESSION['__user__']->getSubject();
$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid(true)." AND rb_cdc.id_classe = ". $_SESSION['__classe__']->get_ID() ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND pagella = 1 AND id_anno = ".$_SESSION['__current_year__']->get_ID();
//print $sel_materie;
try{
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex){
	$ex->redirect();
}
$materie = [];
$idm = [];
while($mt = $res_materie->fetch_assoc()){
	$materie[$mt['id_materia']] = ["id" => $mt['id_materia'], "mat" => $mt['materia']];
	$idm[] = $mt['id_materia'];
}

$alunni = [];
$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE rb_alunni.id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}
while ($row = $res_alunni->fetch_assoc()){
	$alunni[$row['id_alunno']] = $row;
	$alunni[$row['id_alunno']]['ins'] = [];
}

if (isset($_REQUEST['idp'])) {
	$idp = $_REQUEST['idp'];
	$month = $_REQUEST['m'];
}
else {
	$idp = $active[count($active) - 1]['id_pagellino'];
	$month = $active[count($active) - 1]['mese'];
}

$sel_voti = "SELECT materia, alunno FROM rb_segnalazioni_pagellino WHERE id_pagellino = {$idp} AND classe = {$_SESSION['__classe__']->get_ID()} ORDER BY alunno, materia ";
try{
	$res_voti = $db->executeQuery($sel_voti);
} catch (MySQLException $ex){
	$ex->redirect();
}
while ($row = $res_voti->fetch_assoc()) {
	$alunni[$row['alunno']]['ins'][] = $row['materia'];
}

$drawer_label = "Archivio segnalazione insufficienze: mese di {$months[$month]}";
$navigation_label = "registro personale";

include "archivio_segnalazioni.html.php";
