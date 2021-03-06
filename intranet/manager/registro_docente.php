<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "";
switch($_SESSION['__school_order__']) {
	case 1:
		$navigation_label .= "scuola secondaria";
		break;
	case 2:
		$navigation_label .= "scuola primaria";
		break;
}

$cls = null;
if (isset($_REQUEST['cls'])){
	$cls = $_REQUEST['cls'];
}

$ordine_scuola = $_SESSION['__school_order__'];
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	if(date("Y-m-d") > $fine_q){
		$q = 2;
	}
	else{
		$q = 1;
	}
}

switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$label = ", secondo quadrimestre";
}
$doc = $_REQUEST['doc'];
$sel_docente = "SELECT rb_utenti.uid, rb_utenti.nome, rb_utenti.cognome, rb_docenti.*, rb_materie.materia, rb_materie.id_materia FROM rb_docenti, rb_utenti, rb_materie WHERE rb_utenti.uid = rb_docenti.id_docente AND rb_docenti.materia = rb_materie.id_materia AND uid = {$_REQUEST['doc']}";
$res_docente = $db->execute($sel_docente);
$docente = $res_docente->fetch_assoc();
$drawer_label = "Registro personale del docente ". $docente['nome']." ".$docente['cognome'];
//echo $docente['id_materia'];exit;
$_cl = 0;
if ($docente['id_materia'] != 27 && $docente['id_materia'] != 41){
	//echo "IN";exit;
	/*
	 * se supplente, estrazione classi del docente titolare
	 * confrontare poi con quelle in supplenze
	 */
	if ($docente['ruolo'] == 'N') {
		$sel_cls_supp = "SELECT DISTINCT(classe) AS cl FROM rb_classi_supplenza, rb_supplenze WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND id_supplente = {$docente['uid']}";
		$res_cls_supp = $db->executeQuery($sel_cls_supp);
		$cls_supp = array();
		while ($row = $res_cls_supp->fetch_assoc()) {
			$cls_supp[] = $row['cl'];
		}
		$sel_tit_supp = "SELECT DISTINCT(id_docente_assente) AS doc FROM rb_classi_supplenza, rb_supplenze WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND id_supplente = {$docente['uid']}";
		$res_tit_supp = $db->executeQuery($sel_tit_supp);
		$uids_supp = array();
		while ($row = $res_tit_supp->fetch_assoc()) {
			$uids_supp[] = $row['doc'];
		}
		$sel_classes = "SELECT anno_corso, sezione, rb_classi.id_classe, rb_cdc.id_materia, rb_materie.materia FROM rb_classi, rb_cdc, rb_materie WHERE rb_classi.id_classe = rb_cdc.id_classe AND rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente IN (".implode(",", $uids_supp).") AND id_anno = ".$_SESSION['__current_year__']->get_ID()."  AND pagella = 1 ORDER BY rb_classi.sezione, rb_classi.anno_corso";
	}
	else {
		$sel_classes = "SELECT anno_corso, sezione, rb_classi.id_classe, rb_cdc.id_materia, rb_materie.materia FROM rb_classi, rb_cdc, rb_materie WHERE rb_classi.id_classe = rb_cdc.id_classe AND rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente = ".$docente['uid']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()."  AND pagella = 1 ORDER BY rb_classi.sezione, rb_classi.anno_corso";
	}
	//print $sel_classes;
	$res_classes = $db->execute($sel_classes);
	$classi = array();
	$materie = array();
	$id_materie = array();

	while($cl = $res_classes->fetch_assoc()){
		if ($docente['ruolo'] != 'N' || in_array($cl['id_classe'], $cls_supp)) {
			if ($cls == null && $_cl == 0){
				$cls = $cl['id_classe'];
				$_cl = 1;
			}
			if(!isset($classi[$cl['id_classe']])){
				$classi[$cl['id_classe']]['desc'] = $cl['anno_corso'].$cl['sezione'];
				$classi[$cl['id_classe']]['materie'] = array();
				$classi[$cl['id_classe']]['alunni'] = array();
			}
			$classi[$cl['id_classe']]['materie'][$cl['id_materia']] = $cl['materia'];
			$materie[$cl['id_materia']] = $cl['id_materia'];
			if (!in_array($cl['id_materia'], $id_materie)) {
				$id_materie[] = $cl['id_materia'];
			}
		}
	}
}
else {
	header("Location: registro_sostegno.php?doc=".$_REQUEST['doc']);
	exit;
}
$idx = 0;
$start_cls = null;
foreach ($classi as $k => $v){
	if ($idx == 0) $start_cls = $k;
	$sel_alunni = "SELECT cognome, nome, id_alunno FROM rb_alunni WHERE id_classe = {$k} AND attivo = '1' ORDER BY cognome, nome";
	$res_alunni = $db->execute($sel_alunni);
	while ($row = $res_alunni->fetch_assoc()){
		$classi[$k]['alunni'][$row['id_alunno']] = $row;
		$classi[$k]['alunni'][$row['id_alunno']]['materie'] = array();
	}
	$idx++;
}

/*
 * ricerca di supplenti
 */
if ($docente['ruolo'] != 'N') {
	$sel_tit_supp = "SELECT DISTINCT(id_supplente) AS doc FROM rb_classi_supplenza, rb_supplenze WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND id_docente_assente = {$docente['uid']}";
	$res_tit_supp = $db->executeQuery($sel_tit_supp);
	$uids_supp = array();
	while ($row = $res_tit_supp->fetch_assoc()) {
		$uids_supp[] = $row['doc'];
	}
	$uids_supp[] = $docente['uid'];
}
$sel_voti = "SELECT voto, alunno, materia, id_classe FROM rb_voti, rb_alunni WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND materia IN (".implode(',', $materie).") AND alunno = id_alunno {$int_time} ORDER BY id_classe, materia";
$res_voti = $db->execute($sel_voti);
while ($row = $res_voti->fetch_assoc()){
	if (isset($classi[$row['id_classe']])) {
		$classi[$row['id_classe']]['alunni'][$row['alunno']]['materie'][$row['materia']]['voti'][] = $row['voto'];
	}
}
$sel_medie = "SELECT ROUND(AVG(voto), 2) AS voto, alunno, materia, id_classe FROM rb_voti, rb_alunni WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND materia IN (".implode(',', $materie).") AND alunno = id_alunno {$int_time} GROUP BY alunno, materia, id_classe ORDER BY id_classe, materia";
$res_medie = $db->execute($sel_medie);
while ($row = $res_medie->fetch_assoc()){
	if (isset($classi[$row['id_classe']])) {
		$classi[$row['id_classe']]['alunni'][$row['alunno']]['materie'][$row['materia']]['media'] = $row['voto'];
	}
}

$uids_supp[] = $docente['uid'];
$sel_note = "SELECT COUNT(*) AS count, alunno, classe FROM rb_note_didattiche WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente IN (".implode(',', $uids_supp).") GROUP BY alunno, classe";
$res_note = $db->execute($sel_note);
while ($row = $res_note->fetch_assoc()){
	if (isset($classi[$row['classe']])) {
		$classi[$row['classe']]['alunni'][$row['alunno']]['note'] = $row['count'];
	}
}

include "registro_docente.html.php";
