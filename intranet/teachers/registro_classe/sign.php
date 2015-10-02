<?php

/***
 * file: sign.php
 * 
 * permette al docente di firmare il registro per le ore nelle quali ha fatto lezione,
 * indicando anche la materia e l'argomento svolto 
 * 
 */

require_once "../../../lib/start.php";
require_once "../../../lib/ScheduleModule.php";

check_session();
check_permission(DOC_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

if (isset($_REQUEST['cls']) && ((isset($_REQUEST['reload']) && $_REQUEST['reload'] == 1))) {
	require_once $_SESSION['__path_to_root__']."lib/SessionUtils.php";
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
	$id_reg = $db->executeCount("SELECT id_reg FROM rb_reg_classi WHERE data = '".$_REQUEST['data']."' AND id_classe = ".$_REQUEST['cls']);
	$_REQUEST['id_reg'] = $id_reg;
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$sel_dati = "SELECT ingresso, uscita, data FROM rb_reg_classi WHERE id_reg = ".$_REQUEST['id_reg'];
$res_dati = $db->executeQuery($sel_dati);
$dati = $res_dati->fetch_assoc();

$_SESSION['registro']['data'] = $_REQUEST['data'];

/*
 * docenti di sostegno
 */
$sel_sos = "SELECT COUNT(DISTINCT(docente)) FROM rb_assegnazione_sostegno WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND classe = {$_SESSION['__classe__']->get_ID()} ";
$count_sos = $db->executeCount($sel_sos);
// numero di righe in rowspan per il campo ora
$rowspan = $count_sos + 3;

/*
 * calcolo della prima e ultima ora, in caso di ingressi ritardati o uscite anticipate
 */
$schedule_module = $_SESSION['__classe__']->get_modulo_orario();
$d = date("w", strtotime($dati['data']));
$day = $schedule_module->getDay($d);
$starts = $day->getLessonsStartTime();
list($h, $m, $s) = explode(":", $dati['ingresso']);
$ingresso = new RBTime($h, $m, $s);
list($h, $m, $s) = explode(":", $dati['uscita']);
$uscita = new RBTime($h, $m, $s);

$hours = count($starts);
$prima_ora = 1;
$ultima_ora = $hours;

for($i = 1; $i < $hours; $i++){
	if ($ingresso->compare($starts[$i]) == -1){
		$prima_ora = $i -1;
		break;
	}
	else if ($ingresso->equal($starts[$i])){
		$prima_ora = $i;
		break;
	}
}

for($i = $hours; $i > 0; $i--){
	if ($uscita->compare($starts[$i]) == 1){
		$ultima_ora = $i;
		break;
	}
}
/*
 * provvisorio per correggere errore nella visualizzazione delle
 * ore di firma in caso di ingressi particolari, come l'11-13
 * del primo giorno di scuola

$prima_ora = 1;
$dur_time = new RBTime(0, 0, 0);
$dur_time->setTime($uscita->getTime() - $ingresso->getTime());
$min = $day->getHourDuration();
$h = $dur_time->getTime() / $min->getTime();
$ultima_ora = $h;
*/

/* 
 * array di firme, argomenti e id ore
 */
$sel_firme = "SELECT * FROM rb_reg_firme WHERE id_registro = ".$_REQUEST['id_reg']." ORDER BY ora";
//print $sel_firme;
$res_firme = $db->executeQuery($sel_firme);
$firme = array();
$argomenti = array();
$ids = array();
$docenti = array();
$_materie = array();
/*
if ($day->hasCanteen()) {
	$ultima_ora++;
}
*/
for($x = $prima_ora; $x <= $ultima_ora; $x++){
	$firme[$x] = array();
	$argomenti[$x] = array();
	$ids[$x] = $docenti[$x] = $_materie[$x] = 0;
}
if($res_firme->num_rows > 0){
	while($sig = $res_firme->fetch_assoc()){
		if (date('Ymd') < '20131017'){
			$sig['argomento'] = utf8_decode($sig['argomento']);
		}
		$firme[$sig['ora']] = array("docente" => $sig['docente'], "argomento" => $sig['argomento'], "materia" => $sig['materia'], "doc_compresenza" => $sig['docente_compresenza'], "mat_compresenza" => $sig['materia_compresenza']);
		$argomenti[$sig['ora']] = $sig['argomento'];
		$ids[$sig['ora']] = $sig['id'];
		$docenti[$sig['ora']] = $sig['docente'];
		$_materie[$sig['ora']] = $sig['materia'];
		$cdocenti[$sig['ora']] = $sig['docente_compresenza'];
		$c_materie[$sig['ora']] = $sig['materia_compresenza'];
		$firme[$sig['ora']]['sostegno'] = array();
		$sel_support = "SELECT docente FROM rb_reg_firme_sostegno WHERE docente IS NOT NULL AND anno = {$_SESSION['__current_year__']->get_ID()} AND classe = {$_SESSION['__classe__']->get_ID()} AND id_registro = {$_REQUEST['id_reg']} AND ora = {$sig['ora']}";
		$res_support = $db->executeQuery($sel_support);
		if ($res_support->num_rows > 0){
			$index = 1;
			while ($row = $res_support->fetch_assoc()){
				$firme[$sig['ora']]['sostegno'][$index] = $row['docente'];
				$index++;
			}
		}
	}
}
//print_r($firme);

setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = utf8_encode(strftime("%A", strtotime($dati['data'])));

/*
 * estrazione delle materie insegnate
 * prima estraggo le materie base insegnate nella classe
 * poi in base alla materia estratta verifico se necessario
 * estrarre le materie figlie o figlie di figlie (nel caso di lettere)
 */
$materie = array();
if ($_SESSION['__user__']->getSubject() == 27 || $_SESSION['__user__']->getSubject() == 41) {
	$materie[] = array("id_materia" => 27, "has_sons" => 0, "materia" => "Sostegno");
	/*
	 * docente di sostegno: estraggo eventuali attivita` inserite
	 */
	$sel_ass = "SELECT id_alunno, cognome, nome FROM rb_alunni, rb_assegnazione_sostegno WHERE alunno = id_alunno AND anno = {$_SESSION['__current_year__']->get_ID()} AND classe = {$_SESSION['__classe__']->get_ID()} AND docente = {$_SESSION['__user__']->getUid()}";
	$res_ass = $db->execute($sel_ass);
	$alunni = array();
	while ($row = $res_ass->fetch_assoc()){		
		$sel_activities = "SELECT rb_attivita_sostegno.id, data FROM rb_attivita_sostegno, rb_assegnazione_sostegno WHERE rb_attivita_sostegno.alunno = rb_assegnazione_sostegno.alunno AND rb_assegnazione_sostegno.anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$_SESSION['__user__']->getUid()} AND classe = {$_SESSION['__classe__']->get_ID()} AND rb_attivita_sostegno.alunno = {$row['id_alunno']} AND data = '{$_REQUEST['data']}'";
		$res_activities = $db->execute($sel_activities);
		if ($res_activities->num_rows > 0){
			$r = $res_activities->fetch_assoc();
			$row['attivita'] = $r;
		}
		else {
			$row['attivita'] = array("id" => 0, "data" => '');
		}
		$alunni[] = $row;
	}
}
else {
	$materia_base = array();
	$sel_materia = "SELECT rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia FROM rb_docenti, rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_classe = ".$_SESSION['__classe__']->get_ID()." AND rb_docenti.id_docente = ".$_SESSION['__user__']->getUid(true)." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." GROUP BY rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia ORDER BY rb_materie.id_materia";
	//print $sel_materia;
	$res_materia = $db->executeQuery($sel_materia);
	while($record = $res_materia->fetch_assoc()){
		array_push($materia_base, $record);
	}
	//print_r($materia_base);
	/*
	 * primo caso: una sola materia insegnata nella classe:
	 * in questo caso, una seconda estrazione e' necessaria solo 
	 * per Matematica e Storia-Geografia.
	 */
	if($res_materia->num_rows < 2){
		if(!$materia_base[0]['has_sons']){
			array_push($materie, $materia_base[0]);
		}
		else{
			$sel_materie = "SELECT rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia FROM rb_materie WHERE idpadre = ".$materia_base[0]['id_materia'];
			$res_materie = $db->executeQuery($sel_materie);
		}
	}
	/*
	 * secondo caso: insegnante di lettere della classe
	 * sono possibili due varianti: italiano + storia e geografia;
	 * italiano + storia e geografia + approfondimento
	 */
	else{
		foreach($materia_base as $m){
			if($m['has_sons'] == 0){
				// approfondimento e italiano: inserisco nella tabella
				array_push($materie, $m);
			}
			else{
				$sel_materie = "SELECT rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia FROM rb_materie WHERE idpadre = ".$m['id_materia'];
				$res_materie = $db->executeQuery($sel_materie);
			}
		}
	}
	if(isset($res_materie)){
		while($mt = $res_materie->fetch_assoc()){
			array_push($materie, $mt);
		}
	}
}

setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A %d %B %Y", strtotime($_SESSION['registro']['data']));

$navigation_label = "Registro della classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Registro di ". $giorno_str;

include "sign.html.php";
