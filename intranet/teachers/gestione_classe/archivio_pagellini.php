<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 05/01/16
 * Time: 16.37
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

$num_colonne = 1;
$first_column_width = 25;
$column_width = null;
$available_space = 100 - $first_column_width;
$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE id_materia = rb_scrutini.materia AND id_materia <> 40 AND classe = {$_SESSION['__classe__']->get_ID()} AND anno = {$anno} AND id_materia > 2 AND tipologia_scuola = {$ordine_scuola} GROUP BY rb_materie.id_materia, rb_materie.materia ORDER BY rb_materie.id_materia";
try {
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex) {
	$ex->redirect();
}
if ($res_materie->num_rows < 1) {
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie WHERE pagella = 1 AND id_materia > 2 AND tipologia_scuola = {$ordine_scuola}";
	if($musicale != "1"){
		$sel_materie .= " AND id_materia <> 13 ";
	}
	$sel_materie .= "ORDER BY id_materia";
	$res_materie = $db->executeQuery($sel_materie);
}
$materie = [];
while($materia = $res_materie->fetch_assoc()){
	if($materia['materia'] == "Scienze motorie")
		$materia['materia'] = "Smotorie";
	$materie[$materia['id_materia']] = $materia;
}

if ($ordine_scuola == 1) {
	$alt_subject = 46;
}
else {
	$alt_subject = 47;
}
$esonerati = [];
$sel_esonerati = "SELECT alunno FROM rb_esoneri_religione WHERE classe = ".$_SESSION['__classe__']->get_ID();
$res_esonerati = $db->executeQuery($sel_esonerati);
if ($res_esonerati->num_rows > 0) {
	while ($row = $res_esonerati->fetch_assoc()) {
		$esonerati[] = $row['alunno'];
	}
}

$num_materie = $res_materie->num_rows;
$num_colonne += $num_materie;
$column_width = intval($available_space / ($num_colonne - 1));

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Segnalazione insufficienze mese di {$months[$month]}";

include "archivio_pagellini.html.php";
