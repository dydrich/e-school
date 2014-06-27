<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";

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

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$class = $_SESSION['__classe__']->get_ID();
$teacher = $_SESSION['__user__']->getUid();
$teacher_name = $_SESSION['__user__']->getFullName();
$class_name = $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$subject = $_SESSION['__materia__'];

$hid = $_REQUEST['idh'];

/*
 * recupero i dati della firma
 */
$sel_h = "SELECT * FROM rb_reg_firme WHERE id = {$hid}";
try {
	$res_h = $db->executeQuery($sel_h);
} catch (MySQLException $ex) {
	echo "kosql|".$ex->getMessage()."|".$ex->getQuery();
	exit;
}
$hour = $res_h->fetch_assoc();

/*
 * dettaglio alunni per il giorno richiesto
 */
$sel_reg = "SELECT rb_reg_alunni.*, data, nome, cognome FROM rb_reg_alunni, rb_reg_classi, rb_alunni WHERE rb_alunni.id_alunno = rb_reg_alunni.id_alunno AND id_registro = id_reg AND id_registro = {$hour['id_registro']} AND rb_reg_alunni.id_classe = {$class}";
$res_reg = $db->executeQuery($sel_reg);
$reg = $res_reg->fetch_assoc();

$hmod = $_SESSION['__classe__']->get_modulo_orario();
$d = date("w", strtotime($reg['data']));
$day = $hmod->getDay($d);
$starts = $day->getLessonsStartTime();
$hstart = $starts[$hour['ora']];
$duration = $day->getHourDuration();
$end = new RBTime(0, 0, 0);
$end->setTime($hstart->getTime());
$end->add($duration->getTime());

$abs = array();
$absh = array();
$res_reg->data_seek(0);
while ($row = $res_reg->fetch_assoc()){
	if ($row['ingresso'] == ""){
		$abs[] = $row;
	}
	else {
		$min = calcola_minuti_assenza($row['ingresso'], $row['uscita'], $hstart->toString(), $end->toString());
		$dur = $duration->getTime() / 60;
		if ($min > 0 && $min < ($dur)){
			$absh[] = array($row['nome'], $row['cognome'], $min);
		}
		else if ($min == $dur){
			$abs[] = $row;
		}
	}
}

setlocale(LC_TIME, "it_IT");
$label = ucfirst(utf8_encode(strftime("%A %d %B", strtotime($reg['data']))));

include "show_hour_absences.html.php";

?>