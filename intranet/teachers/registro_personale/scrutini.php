<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";
require_once "../../../lib/RBUtilities.php";

ini_set("display_errors", DISPLAY_ERRORS);

function calcola_minuti_assenza($ingresso, $uscita, $inizio, $fine){
	// mktime(ore, minuti, secondi, mese, giorno, anno)
	if($ingresso <= $inizio)
		$start = $inizio;
	else
		$start = $ingresso;

	if($fine >= $uscita)
		$end = $uscita;
	else
		$end = $fine;

	//print ("Inizia $start e finisce $end\n");
	$s = explode(":", $start);
	$e = explode(":", $end);
	$ore = intval($s[0], $base = 10);
	$minuti = intval($s[1], $base = 10);
	$ore2 = intval($e[0], $base = 10);
	$minuti2 = intval($e[1], $base = 10);
	//print ("Ore $ore vs. Ore $ore2\nMinuti $minuti vs $minuti2\n\n");

	$from = mktime($hour = $ore, $minute = $minuti, $second = 0, $month = 12, $day = 1, $year = 1970, $is_dst = -1);
	$to = mktime($hour = $ore2, $minute = $minuti2, $second = 0, $month = 12, $day = 1, $year = 1970, $is_dst = -1);
	//print("From $from to $to\n");
	return 60 - (($to - $from) / 60);
}

function get_absences_time($db, $teacher, $materia, $anno, $abs_time, $class, $id_alunno, $schedule_module, $fine_q){
	global $ordine_scuola;
	if ($ordine_scuola == 2){
		return 0;
	}
	$sel_hours = "SELECT id_reg, rb_reg_classi.data, rb_reg_classi.ingresso, rb_reg_classi.uscita, ora FROM rb_reg_classi, rb_reg_firme WHERE rb_reg_classi.id_reg = rb_reg_firme.id_registro AND docente = {$teacher} AND materia = {$materia} AND id_classe = $class AND id_anno = ".$_SESSION['__current_year__']->get_ID()." $abs_time  ORDER BY data, ingresso DESC, ora ASC";
	try{
		$res_hours = $db->executeQuery($sel_hours);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	$hours_count = $res_hours->num_rows;
	$hours = array();
	$id_reg = array();
	$absence_time = 0;
	//print(time());
	while($row = $res_hours->fetch_assoc()){

		$hours[$row['id_reg']] = $row;

		$day_number = date("w", strtotime($row['data']));
		$day = $schedule_module->getDay($day_number);
		$starts = $day->getLessonsStartTime();
		$h_dur = $day->getHourDuration();
		$lesson_times = array();
		foreach ($starts as $k => $s){
			$st = $s->toString(RBTime::$RBTIME_SHORT);
			$s->add($h_dur->getTime());
			$en = $s->toString(RBTime::$RBTIME_SHORT);
			$lesson_times[$k] = array($st, $en);
		}
		$add_for_hour = $h_dur->getTime() / 60;

		//$hours[$row['id_reg']] = $row;
		$sel_student_time = "SELECT ingresso, uscita FROM rb_reg_alunni WHERE id_alunno = $id_alunno AND id_registro = ".$row['id_reg'];
		try{
			$res_student_time = $db->executeQuery($sel_student_time);
		} catch (MySQLException $ex){
			//$ex->redirect();
		}
		$_res = $res_student_time->fetch_assoc();
		$enter = $exit = "";
		$enter = $_res['ingresso'];
		$exit = $_res['uscita'];
		// calcolo l'eventuale assenza
		if($enter <= $lesson_times[$row['ora']][0]){
			// l'alunno era presente all'inizio dell'ora
			if($exit <= $lesson_times[$row['ora']][0]){
				// alunno assente per l'intera ora
				$absence_time += 60;
			}
			else if($exit >= $lesson_times[$row['ora']][1]){
				//print ("Alunno presente per l'intera ora\n");
			}
			else if($exit < $lesson_times[$row['ora']][1]){
				// l'alunno e' uscito prima
				$m = calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
				$absence_time += $m;
			}
		}
		else if($enter > $lesson_times[$row['ora']][0]){
			// l'alunno non era presente all'inizio dell'ora
			if($enter >= $lesson_times[$row['ora']][1]){
				//print ("Alunno assente per l'intera ora\n");
				$absence_time += 60;
			}
			else if($exit >= $lesson_times[$row['ora']][1]){
				// alunno entrato in ritardo
				$m = calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
				$absence_time += $m;
			}
			else{
				// alunno entrato in ritardo e uscito prima
				$m = calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
				$absence_time += $m;
			}
		}
		//$v['absence_time'] += calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
	}
	$st_absence = minutes2hours($absence_time, "/");
	return $st_absence;
}

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

require_once "../reload_class_in_session.php";

if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && ($_SESSION['__user__']->getSubject() == 27)){
	header("Location: scrutini_classe.php");
}

if ($_SESSION['__user__']->isSupplyTeacher()) {
	$last_day = $db->executeCount("SELECT MAX(data_fine_supplenza) FROM rb_supplenze, rb_classi_supplenza WHERE rb_supplenze.id_supplenza = rb_classi_supplenza.id_supplenza AND classe = {$_SESSION['__classe__']->get_ID()}");
	if ($last_day < date("Y-m-d")) {
		$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
		header("Location: {$_SESSION['__path_to_reg_home__']}no_permission.php");
	}
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$schedule_module = $_SESSION['__classe__']->get_modulo_orario();
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$_REQUEST['tot'] = 0;

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$date_max = date("Y-m-d", strtotime($fine_q." +35 days"));
	if(date("Y-m-d") > $date_max){
		$q = 2;
	}
	else{
		$q = 1;
	}
}
if($q == 1)
	$label = " primo quadrimestre";
else
	$label = " finali";

/*
 * verifica se scrutini ancora aperti, per modifica
 */
$suffix = '';
if ($ordine_scuola == 2) {
	$suffix = '_sp';
}
$sel_scr_op = "SELECT stato_scrutinio{$suffix}, quadrimestre FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY 
quadrimestre DESC";
$res_scr_op = $db->execute($sel_scr_op);
$scr_1q = $scr_2q = 0;
while ($row = $res_scr_op->fetch_assoc()){
	if ($row['quadrimestre'] == 1){
		$scr_1q = $row['stato_scrutinio'];
	}
	else {
		$scr_2q = $row['stato_scrutinio'];
	}
}

$readonly = false;
if (($q == 1 && $scr_1q == 1) || ($q == 2 && $scr_2q == 1)){
	$readonly = true;
}

$mat = $_SESSION['__user__']->getSubject();

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

/*
 * controllo materia alternativa
 */
if ($ordine_scuola == 1) {
	$alt_subject = 46;
	$id_religione = 26;
}
else {
	$alt_subject = 47;
	$id_religione = 30;
}
$sel_alt_sub = "SELECT COUNT(*) FROM rb_materia_alternativa WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND classe = ".$_SESSION['__classe__']->get_ID()." AND docente = ".$_SESSION['__user__']->getUid();
$res_alt_sub = $db->executeCount($sel_alt_sub);
$subject_number = count($_SESSION['__subjects__']);
if ($res_alt_sub > 0) {
	$subject_number++;
}

if($subject_number > 0) {
	$k = 0;
	if (isset($_REQUEST['subject']) && $_REQUEST['subject'] == $alt_subject) {
		$idm = $alt_subject;
		$_mat = "Mat. alt.";
	}
	else {
		foreach ($_SESSION['__subjects__'] as $mt) {
			//print "while";
			if (isset($_REQUEST['subject'])) {
				if ($_REQUEST['subject'] == $mt['id']) {
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
			}
			else {
				if (isset($_SESSION['__materia__'])) {
					if ($_SESSION['__materia__'] == $mt['id']) {
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
					else {
						if ($k == 0) {
							$idm = $mt['id'];
							$_mat = $mt['mat'];
						}
					}
				}
				else {
					if ($k == 0) {
						//print "k==0";
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
				}
			}
			$k++;
		}
	}
	$_SESSION['__materia__'] = $idm;
}

if(isset($_REQUEST['subject'])) {
	$_SESSION['__materia__'] = $_REQUEST['subject'];
}

/*
 * controllo per la materia alternativa:
 * se richiesta va caricato il dato di religione
 */
$load_mat = $_SESSION['__materia__'];
if ($load_mat == $alt_subject) {
	$load_mat = $id_religione;
}

$sel_dati = "SELECT rb_alunni.cognome, rb_alunni.nome, alunno, voto, assenze FROM rb_alunni LEFT JOIN rb_scrutini ON id_alunno = alunno WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND id_classe = ". $_SESSION['__classe__']->get_ID() ." AND classe = id_classe AND quadrimestre = $q AND materia = {$load_mat} AND attivo = '1' ORDER BY cognome, nome";
$res_dati = $db->execute($sel_dati);
$studenti = [];
while ($row = $res_dati->fetch_assoc()) {
	$studenti[$row['alunno']] = $row;
}

$esonerati = array();
if ($_SESSION['__user__']->getSubject() == 26 || $_SESSION['__materia__'] == 30 || $_SESSION['__materia__'] == 46 || $_SESSION['__materia__'] == 47) {
	/*
	 * esoneri religione
	 */
	$sel_esonerati = "SELECT alunno FROM rb_esoneri_religione WHERE classe = ".$_SESSION['__classe__']->get_ID();
	$res_esonerati = $db->executeQuery($sel_esonerati);
	if ($res_esonerati->num_rows > 0) {
		while ($row = $res_esonerati->fetch_assoc()) {
			$esonerati[] = $row['alunno'];
		}
	}
}

$anno = $_SESSION['__current_year__']->get_ID();
$teacher = $_SESSION['__user__']->getUid();
$signature = $teacher.";".$_SESSION['__materia__'];
$class = $_SESSION['__classe__']->get_ID();

/*
 * caricamento medie voto e assenze, con registrazione delle assenze
 */
switch($q){
	case 0:
		$int_time = "AND data_voto < NOW()";
		$abs_time = "AND data < NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$abs_time = "AND data <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$abs_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = ", secondo quadrimestre";
}


foreach ($studenti as $k => $item) {
	$sel = "SELECT ROUND(AVG(voto), 2) AS voto
		FROM rb_voti
		WHERE alunno = $k
		AND materia = {$_SESSION['__materia__']} AND anno = $anno $int_time";
	$res = $db->executeCount($sel);
	$studenti[$k]['media_voto'] = $res;
	if ($ordine_scuola == 1){
		$st_absence = get_absences_time($db, $teacher, $_SESSION['__materia__'], $anno, $abs_time, $class, $k, $schedule_module, $fine_q);
		if(strlen($st_absence) > 1){
			list($h, $m) = explode(":", $st_absence);
			$hs = intval($h);
			if($m > 30)
				$hs++;
			if($_REQUEST['tot'] == 1)
				$ret = $hs;
			else
				$ret = $st_absence;
		}
		else{
			if($_REQUEST['tot'] == 1)
				$ret = $st_absence;
			else
				$ret = "";
			$hs = 0;
		}
		$upd_abs = "UPDATE rb_scrutini SET assenze = $hs WHERE alunno = $k AND materia = {$_SESSION['__materia__']} AND anno = $anno AND quadrimestre = $q";
		$db->executeUpdate($upd_abs);
	}
	else {
		$ret = "";
		$hs = 0;
	}
	$studenti[$k]['assenze'] = $hs;
	$studenti[$k]['assenze_calcolate'] = $ret;
	//$studenti[$k]['st_absence'] = $st_absence;
}

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Scrutini".$label;

include "scrutini.html.php";
