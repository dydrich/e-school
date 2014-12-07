<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if(isset($_REQUEST['subject'])){
	$_SESSION['__materia__'] = $_REQUEST['subject'];
}

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

require_once "../reload_class_in_session.php";

$teacher = $_SESSION['__user__']->getUid();
if ($_SESSION['__user__']->isSupplyTeacher()) {
	$teacher .= ",".$_SESSION['__user__']->getUid(true);
}
$subject = $_SESSION['__materia__'];
$signature = $teacher.";".$subject;
$class = $_SESSION['__classe__']->get_ID();

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$schedule_module = $_SESSION['__classe__']->get_modulo_orario();
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

/*
 * array che contiene l'orario d'inizio e fine di ogni ora di lezione
 */
/*
$lesson_times = array();
for($i = 1; $i < 10; $i++){
	$start = $end = "";
	if($i > 5)
		$k = $i + 1;
	else
		$k = $i;
	$start = (($k + 7) > 9) ? ($k + 7) : "0".($k + 7);
	$start .= ":30";
	$end = (($k + 8) > 9) ? ($k + 8) : "0".($k + 8);
	$end .= ":30";
	$lesson_times[$i] = array($start, $end);
}
*/

/*
 * query #1: estrazione degli alunni della classe
 */
$sel_students = "SELECT * FROM rb_alunni WHERE id_classe = $class AND attivo = '1' ORDER BY cognome, nome";
try{
	$res_students = $db->executeQuery($sel_students);
} catch (MySQLException $ex){
	$ex->redirect();
}
$students = array();
while($student = $res_students->fetch_assoc()){
	$student['absence_time'] = 0;
	$student['absence_time_1q'] = 0;
	$student['absence_time_2q'] = 0;
	$students[$student['id_alunno']] = $student;
}
$res_students->free();

/*
 * query #2: elenco delle ore con id_registro
 * ottengo: 
 *  - numero ore di lezione per la materia
 *  - elenco degli id registro per le ore in esame (memorizzato in un array). 
 *  Ogni elemento dell'array contiene: data, ora ingresso e uscita della classe.
 *  All'interno del ciclo che attraversa tutti gli id_reg in esame,
 *  una seconda query recupera gli orari di ingresso e uscita per ogni alunno
 */
$sel_hours = "SELECT id_reg, rb_reg_classi.data, rb_reg_classi.ingresso, rb_reg_classi.uscita, ora FROM rb_reg_classi, rb_reg_firme WHERE rb_reg_classi.id_reg = rb_reg_firme.id_registro AND materia = {$subject} AND id_classe = $class AND id_anno = ".$_SESSION['__current_year__']->get_ID()."  ORDER BY data, ingresso DESC, ora ASC";

try{
	$res_hours = $db->executeQuery($sel_hours);
} catch (MySQLException $ex){
	$ex->redirect();
}
$hours_count = $res_hours->num_rows;
$q1_hours_count = $q2_hours_count = 0;
//print $hours_count;
$hours = array();
$id_reg = array();
//print(time());
while($row = $res_hours->fetch_assoc()){
	$hours[$row['id_reg']] = $row;
	reset($students);
	if($row['data'] <= $fine_q){
		$q1_hours_count++;
		$q = "absence_time_1q";
	}
	else{
		$q2_hours_count++;
		$q = "absence_time_2q";
	}
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
	
	while(list($k, $v) = each($students)){
		$sel_student_time = "SELECT ingresso, uscita FROM rb_reg_alunni WHERE id_alunno = $k AND id_registro = ".$row['id_reg'];
		try{
			$res_student_time = $db->executeQuery($sel_student_time);
		} catch (MySQLException $ex){
			$ex->redirect();
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
				$students[$k]['absence_time'] += $add_for_hour;
				$students[$k][$q] += $add_for_hour;
				/*
				 * bug check
				 */
				//if($k == 3793){
				//	print $row['data']."==".$row['ora']."==>".$exit."<==".$lesson_times[$row['ora']][0]."<br>";
				//}
				/*
				 * bug check stop
				 */
			}
			else if($exit >= $lesson_times[$row['ora']][1]){
				//print ("Alunno presente per l'intera ora\n");
			}
			else if($exit < $lesson_times[$row['ora']][1]){
				// l'alunno e' uscito prima
				$m = calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
				$students[$k]['absence_time'] += $m;
				$students[$k][$q] += $m;
			}
		}
		else if($enter > $lesson_times[$row['ora']][0]){
			// l'alunno non era presente all'inizio dell'ora
			if($enter >= $lesson_times[$row['ora']][1]){
				//print ("Alunno assente per l'intera ora\n");
				$students[$k]['absence_time'] += $add_for_hour;
				$students[$k][$q] += $add_for_hour;
			}
			else if($exit >= $lesson_times[$row['ora']][1]){
				// alunno entrato in ritardo
				$m = calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
				$students[$k]['absence_time'] += $m;
				$students[$k][$q] += $m;
			}
			else{
				// alunno entrato in ritardo e uscito prima
				$m = calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
				$students[$k]['absence_time'] += $m;
				$students[$k][$q] += $m;
			}
		}	
		//$v['absence_time'] += calcola_minuti_assenza($enter, $exit, $lesson_times[$row['ora']][0], $lesson_times[$row['ora']][1]);
	}
}

if(count($_SESSION['__subjects__']) > 0) {
	$k = 0;
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
	$_SESSION['__materia__'] = $idm;
}

$mat = $_SESSION['__user__']->getSubject();
$change_subject = new ChangeSubject("hid", "", "position: absolute; width: 180px; height: 55px; display: none", "div", $_SESSION['__subjects__']);
$change_subject->createLink("text-decoration: none; text-transform: uppercase; font-weight: bold");
$change_subject->setJavascript('', 'jquery');

$navigation_label = "registro personale - ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Assenze orarie per materia";

include "absences.html.php";
