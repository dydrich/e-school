<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/09/14
 * Time: 17.53
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/RBTime.php";

check_session();
check_permission(DOC_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if(!isset($_REQUEST['cls'])){
	$_REQUEST['cls'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$schedule_module = $_SESSION['__classe__']->get_modulo_orario();
$ld = $schedule_module->getDays();

setlocale(LC_TIME, "it_IT.utf8");
$today = date("Y-m-d");

$current_day = date("w");
if ($current_day == 0) {
	$current_day = 7;
	//$yesterday = new DateTime($today." 00:00:00");
	//$ref_day = $yesterday->sub(new DateInterval("P1D"));
	//$today = $ref_day->format("Y-m-d");
}
$first_day = 1;
$last_day = count($ld);
$days = array();
for ($x = 1; $x <= $last_day; $x++) {
	$diff = $x - $current_day;
	$p = new DateTime($today." 00:00:00");
	if ($diff < 0) {
		$x_date = $p->sub(new DateInterval("P".abs($diff)."D"));
	}
	else if ($diff > 0) {
		$x_date = $p->add(new DateInterval("P".abs($diff)."D"));
	}
	else {
		$x_date = $p;
	}
	$id_reg = $db->executeCount("SELECT id_reg FROM rb_reg_classi WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND data = '".$x_date->format("Y-m-d")."'");
	//echo $id_reg." =>"."SELECT id_reg FROM rb_reg_classi WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND data = '".$x_date->format("Y-m-d")."'<br />";
	$sel_assenze = "SELECT cognome, nome FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE id_reg = id_registro AND rb_reg_alunni.ingresso IS NULL AND id_reg = {$id_reg} AND rb_reg_classi.id_classe = ".$_SESSION['__classe__']->get_ID()." AND rb_reg_classi.id_anno = ".$_SESSION['__current_year__']->get_ID()." AND rb_reg_alunni.id_alunno = rb_alunni.id_alunno ORDER BY cognome, nome ";
	$res_assenze = $db->execute($sel_assenze);
	$assenti = "";
	$_assenti = array();
	if ($res_assenze->num_rows > 0) {
		while ($row = $res_assenze->fetch_assoc()) {
			$_assenti[] = $row['cognome']." ".substr($row['nome'], 0, 1).".";
		}
		$assenti = implode(", ", $_assenti);
	}
	/*
	 * firme
	 */
	$day = $schedule_module->getDay($x);
	$starts = $day->getLessonsStartTime();
	$mensa = $day->hasCanteen();

	$hours = count($starts);
	$prima_ora = 1;
	$ultima_ora = $hours;

	$sel_firme = "SELECT rb_reg_firme.*, cognome, nome, rb_materie.materia as desc_mat FROM rb_reg_firme, rb_materie, rb_utenti WHERE id_registro = ".$id_reg." AND docente = uid AND rb_reg_firme.materia = rb_materie.id_materia ORDER BY ora";
//print $sel_firme;
	$res_firme = $db->executeQuery($sel_firme);
	$firme = array();
	$argomenti = array();
	$ids = array();
	$docenti = array();
	$_materie = array();
	for($k = $prima_ora; $k <= $ultima_ora; $k++){
		$firme[$k] = array();
	}
	if($res_firme->num_rows > 0){
		while($sig = $res_firme->fetch_assoc()){
			$firme[$sig['ora']] = array("docente" => $sig['cognome']." ".substr($sig['nome'], 0, 1).".", "argomento" => $sig['argomento'], "materia" => $sig['desc_mat'], "doc_compresenza" => $sig['docente_compresenza'], "mat_compresenza" => $sig['materia_compresenza']);
			$firme[$sig['ora']]['sostegno'] = array();
			$sel_support = "SELECT cognome, nome FROM rb_reg_firme_sostegno, rb_utenti WHERE docente IS NOT NULL AND docente = uid AND classe = {$_SESSION['__classe__']->get_ID()} AND id_registro = {$id_reg} AND ora = {$sig['ora']}";
			$res_support = $db->executeQuery($sel_support);
			if ($res_support->num_rows > 0){
				$index = 1;
				while ($row = $res_support->fetch_assoc()){
					$firme[$sig['ora']]['sostegno'][$index] = $row['cognome']." ".substr($row['nome'], 0, 1).".";
					$firme[$sig['ora']]['docente'] .= ", ".$row['cognome']." ".substr($row['nome'], 0, 1).".";
					$index++;
				}
			}
		}
	}

	$days[$x] = array("date_print" => strftime("%a %d %B", strtotime($x_date->format("Y-m-d"))),
	                  "date_short_print" => strftime("%a %d", strtotime($x_date->format("Y-m-d"))),
	                  "date" => $x_date->format("Y-m-d"),
	                  "id_reg" => $id_reg,
	                  "assenti" => $assenti,
	                  "firme" => $firme,
	                  "hours" => $hours,
	                  "mensa" => $mensa

	);
}

include "riepilogo_registro_classe.html.php";
