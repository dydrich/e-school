<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

$cls = null;
if (isset($_REQUEST['cls'])){
	$cls = $_REQUEST['cls'];
}

$ordine_scuola = $_SESSION['__school_order__'];
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
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

$_cl = 0;
if ($docente['id_materia'] != 27 && $docente['id_materia'] != 41){
	$sel_classes = "SELECT anno_corso, sezione, rb_classi.id_classe, rb_cdc.id_materia, rb_materie.materia FROM rb_classi, rb_cdc, rb_materie WHERE rb_classi.id_classe = rb_cdc.id_classe AND rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente = ".$docente['uid']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()."  AND pagella = 1 ORDER BY rb_classi.sezione, rb_classi.anno_corso";
	//print $sel_classes;
	$res_classes = $db->execute($sel_classes);
	$classi = array();
	while($cl = $res_classes->fetch_assoc()){
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
	}
}
else {
	header("Location: registro_sostegno.php?doc=".$_REQUEST['doc']);
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
$sel_voti = "SELECT voto, alunno, materia, id_classe FROM rb_voti, rb_alunni WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$doc} AND alunno = id_alunno {$int_time} ORDER BY id_classe, materia";
$res_voti = $db->execute($sel_voti);
while ($row = $res_voti->fetch_assoc()){
	$classi[$row['id_classe']]['alunni'][$row['alunno']]['materie'][$row['materia']]['voti'][] = $row['voto'];
}
$sel_medie = "SELECT ROUND(AVG(voto), 2) AS voto, alunno, materia, id_classe FROM rb_voti, rb_alunni WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$doc} AND alunno = id_alunno {$int_time} GROUP BY alunno, materia, id_classe ORDER BY id_classe, materia";
$res_medie = $db->execute($sel_medie);
while ($row = $res_medie->fetch_assoc()){
	$classi[$row['id_classe']]['alunni'][$row['alunno']]['materie'][$row['materia']]['media'] = $row['voto'];
}

$sel_note = "SELECT COUNT(*) AS count, alunno, classe FROM rb_note_didattiche WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$doc} GROUP BY alunno, classe"; 
$res_note = $db->execute($sel_note);
while ($row = $res_note->fetch_assoc()){
	$classi[$row['classe']]['alunni'][$row['alunno']]['note'] = $row['count'];
}

include "registro_docente.html.php";
