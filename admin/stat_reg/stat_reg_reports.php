<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area_label__'] = "Area amministrazione";

$classes_table = "rb_classi";
$school_order = 0;
if (isset($_GET['school_order'])){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$school_order = $_GET['school_order'];
}
else if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school_order = $_SESSION['__school_order__'];
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
	$school_order = $_SESSION['school_order'];
}

$school_orders = array("1" => "scuola media", "2" => "scuola primaria", "3" => "scuola dell'infanzia");

$sel_cls = "SELECT id_classe, anno_corso, sezione, $classes_table.ordine_di_scuola, rb_sedi.nome FROM {$classes_table}, rb_sedi, rb_tipologia_scuola WHERE sede = rb_sedi.id_sede AND {$classes_table}.ordine_di_scuola = id_tipo AND rb_tipologia_scuola.attivo = 1 ORDER BY sezione, anno_corso, sede ";
$res_cls = $db->execute($sel_cls);
$classi = array();
while ($row = $res_cls->fetch_assoc()){
	$classi[$row['id_classe']] = array();
	$classi[$row['id_classe']]['cls'] = $row;
	$classi[$row['id_classe']]['alunni'] = array();
}

$sel_alunni = "SELECT rb_alunni.*, anno_corso, sezione FROM rb_alunni, rb_classi WHERE rb_classi.id_classe = rb_alunni.id_classe AND attivo = '1' ORDER BY sezione, anno_corso, rb_alunni.cognome, rb_alunni.nome";
//print $sel_alunni;
$res_alunni = $db->execute($sel_alunni);
$num_alunni = $res_alunni->num_rows;
while($alunno = $res_alunni->fetch_assoc()){
	$alunno['dw'] = "";
	if (isset($classi[$alunno['id_classe']])){
		$classi[$alunno['id_classe']]['alunni'][$alunno['id_alunno']] = $alunno;
	}
}

$sel_pagelle = "SELECT * FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY quadrimestre";
try{
	$res_pagelle = $db->executeQuery($sel_pagelle);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}
$pagelle = array();
$field = array("data_pubblicazione", "ora_pubblicazione");
if ($_SESSION['__school_order__'] == 2){
	$field[0] = "data_pubblicazione_sp";
	$field[1] = "ora_pubblicazione_sp";
}
while($pag = $res_pagelle->fetch_assoc()){
	if ($pag[$field[0]] != ""){
		$pagelle[$pag['quadrimestre']]['data_pubblicazione']  = $pag[$field[0]];
		$pagelle[$pag['quadrimestre']]['ora_pubblicazione']   = substr($pag[$field[1]], 0, 5);
		$pagelle[$pag['quadrimestre']]['id'] = $pag['id_pagella'];
	}
	else {
		$pagelle[$pag['quadrimestre']] = "";
	}
}

$quadrimestre = null;
$id = null;
if ($pagelle[2]['data_pubblicazione'] == "" || ($pagelle[2]['data_pubblicazione'] <= date("Y-m-d"))){
	$quadrimestre = 2;
}
else {
	$quadrimestre = 1;
}
$id = $pagelle[$quadrimestre]['id'];

$sel_dw = "SELECT MAX(data_lettura) AS max, alunno, id_classe FROM rb_lettura_pagelle, rb_alunni WHERE alunno = id_alunno AND id_pubblicazione = {$id} GROUP BY alunno, id_classe";
$res_dw = $db->execute($sel_dw);
while ($r = $res_dw->fetch_assoc()){
	$max = format_date(substr($r['max'], 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/")." ".substr($r['max'], 10);
	if (isset($classi[$r['id_classe']])) {
		$classi[$r['id_classe']]['alunni'][$r['alunno']]['dw'] = $max;
	}
}

$navigation_label = "statistiche registro";
$drawer_label = "Pagelle scaricate";
if ($school_order != 0) {
	$drawer_label .= ": ".$school_orders[$school_order];
}

include "stat_reg_reports.html.php";
