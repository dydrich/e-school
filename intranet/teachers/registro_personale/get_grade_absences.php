<?php

require_once "../../../lib/start.php";
require_once "../../../lib/RBUtilities.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$schedule_module = $_SESSION['__classe__']->get_modulo_orario();
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

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

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$id_alunno = $_REQUEST['alunno'];
$materia = $_SESSION['__materia__'];
$quadrimestre = $_REQUEST['q'];
$anno = $_SESSION['__current_year__']->get_ID();
$teacher = $_SESSION['__user__']->getUid();
$signature = $teacher.";".$materia;
$class = $_SESSION['__classe__']->get_ID();

switch($quadrimestre){
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

$sel = "SELECT ROUND(AVG(voto), 2) FROM rb_voti WHERE alunno = $id_alunno AND materia = $materia AND anno = $anno $int_time";

switch($_REQUEST['req']){
	case "grade":
		$ret = $db->executeCount($sel);
		if ($materia == 26 || $materia == 30){
			$ret = $voti_religione[RBUtilities::convertReligionGrade($ret)];
		}
		$ret = "(".$ret.")";
		break;
	case "absences":
		/*
		* assenze
		*/
		if ($ordine_scuola == 1){
			$st_absence = get_absences_time($db, $teacher, $materia, $anno, $abs_time, $class, $id_alunno, $schedule_module, $fine_q);
			if(strlen($st_absence) > 1){
				list($h, $m) = explode(":", $st_absence);
				$hs = intval($h);
				if($m > 30)
					$hs++;
				if($_REQUEST['tot'] == 1)
					$ret = $hs;
				else
					$ret = "(".$st_absence.")";
			}
			else{
				if($_REQUEST['tot'] == 1)
					$ret = $st_absence;
				else
					$ret = "";
				$hs = 0;
			}
			$upd_abs = "UPDATE rb_scrutini SET assenze = $hs WHERE alunno = $id_alunno AND materia = $materia AND anno = $anno AND quadrimestre = $quadrimestre";
			$db->executeUpdate($upd_abs);
		}
		else {
			$ret = "";
		}
		break;
	case "all":
		$grd = $db->executeCount($sel);
		if ($materia == 26 || $materia == 30){
			$grd = $voti_religione[RBUtilities::convertReligionGrade($grd)];
		}
		$st_absence = get_absences_time($db, $teacher, $materia, $anno, $abs_time, $class, $id_alunno, $schedule_module);
		$ret = $grd."#";
		if(strlen($st_absence) > 1){
			list($h, $m) = explode(":", $st_absence);
			$hs = intval($h);
			if($m > 30)
				$hs++;
			$ret .= $hs."#";
		}
		
		$ret .= $st_absence;
		break;
}

header("Content-type: text/plain");
print "$ret";
exit;

?>