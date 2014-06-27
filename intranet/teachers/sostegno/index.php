<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if(!isset($_REQUEST['cls'])){
	$_REQUEST['cls'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}

$param = "";
if (isset($_REQUEST['id_st'])){
	$param = "AND alunno = {$_REQUEST['id_st']}";
}

$sel_st = "SELECT cognome, nome, data_nascita, luogo_nascita, alunno FROM rb_alunni, rb_assegnazione_sostegno WHERE docente = {$_SESSION['__user__']->getUid()} AND anno = {$_SESSION['__current_year__']->get_ID()} AND id_alunno = alunno {$param} AND classe = {$_REQUEST['cls']} ";
$res_st = $db->execute($sel_st);

if ($res_st->num_rows == 1){
	$row = $res_st->fetch_assoc();
	$_SESSION['__sp_student__'] = $row;
}
$sel_indirizzo = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = {$row['alunno']}";
$res_indirizzo = $db->execute($sel_indirizzo);
$_SESSION['__sp_student__']['indirizzo'] = array();
if ($res_indirizzo->num_rows > 0){
	$indirizzo = $res_indirizzo->fetch_assoc();
	$_SESSION['__sp_student__']['indirizzo'] = $indirizzo;
}
$sel_dati = "SELECT * FROM rb_dati_sostegno WHERE alunno = {$row['alunno']}";
$res_dati = $db->execute($sel_dati);
$_SESSION['__sp_student__']['dati'] = array();
if ($res_dati->num_rows > 0){
	$dati = $res_dati->fetch_assoc();
	$_SESSION['__sp_student__']['dati'] = $dati;
}

$navigation_label = "Registro del docente di sostegno ";

include "index.html.php";