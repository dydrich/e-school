<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/11/14
 * Time: 23.06
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/ClassbookData.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$stid = $_REQUEST['stid'];

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['q'])) {
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

$label = "";
$anno = $_SESSION['__current_year__']->get_ID();

$_tday = date("Y-m-d");

switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$scr_par = "";
		$par_tot = "AND data <= NOW()";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$scr_par = "AND quadrimestre = {$q}";
		$label .= ", primo quadrimestre";
		$fq = $fine_q;
		$min = $_tday < $fq ? $_tday : $fq;
		//$par_tot = "AND data <= '{$min}'";
		$par_tot = "AND data <= NOW()";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$scr_par = "AND quadrimestre = {$q}";
		$label .= ", secondo quadrimestre";
		//$par_tot = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$par_tot = "AND data <= NOW()";
}

/*
 * assenze
 */
$module = $_SESSION['__classe__']->get_modulo_orario();
$classbook_data = new ClassbookData($_SESSION['__classe__'], $school_year, $par_tot, $db);
$totali = $classbook_data->getClassSummary();
$studentData = $classbook_data->getStudentSummary($stid);
$perc_day = round((($studentData['absences'] / $totali['giorni']) * 100), 2);
$absences = new RBTime(0, 0, 0);
$absences->setTime($totali['ore']->getTime() - $studentData['presence']->getTime());
$perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);

/*
 * ritardi e uscite anticipate
 */
$sel_somma_ritardi = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(rb_reg_alunni.ingresso, rb_reg_classi.ingresso))))) AS ore_ritardo, COUNT(rb_reg_alunni.ingresso) AS giorni_ritardo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso <> '00:00:00' AND id_alunno = $stid AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso ";
$res_somma_ritardi = $db->executeQuery($sel_somma_ritardi);
$somma_ritardi = $res_somma_ritardi->fetch_assoc();
$sel_somma_uscite  = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(CASE WHEN rb_reg_classi.uscita > '13:30:00' THEN (ADDTIME(rb_reg_classi.uscita, '-1:00:00')) ELSE rb_reg_classi.uscita END, rb_reg_alunni.uscita))))) AS ore_perse, COUNT(rb_reg_alunni.uscita) AS giorni_anticipo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND id_alunno = $stid AND rb_reg_classi.uscita > rb_reg_alunni.uscita ";
$res_somma_uscite = $db->executeQuery($sel_somma_uscite);
$somma_uscite = $res_somma_uscite->fetch_assoc();


/*
 * note disciplinari
 */
$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $stid";
$sel_tipi = "SELECT * FROM rb_tipi_note_disciplinari ORDER BY id_tiponota";
$sel_note = "SELECT rb_note_disciplinari.*, rb_utenti.cognome, rb_utenti.nome, rb_tipi_note_disciplinari.descrizione AS tipo_nota, rb_tipi_note_disciplinari.id_tiponota FROM rb_note_disciplinari, rb_tipi_note_disciplinari, rb_utenti WHERE id_tiponota = tipo AND alunno = {$stid} AND docente = uid ".$par_tot." AND anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY data DESC";
//print $sel_note;
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_note = $db->executeQuery($sel_note);
	$res_tipi = $db->executeQuery($sel_tipi);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

/*
 * note didattiche
 */
$sel_tipi_nd = "SELECT * FROM rb_tipi_note_didattiche ORDER BY id_tiponota";
$sel_note_nd = "SELECT rb_note_didattiche.tipo, rb_tipi_note_didattiche.descrizione AS tipo_nota, COUNT(id_nota) AS count FROM rb_note_didattiche, rb_tipi_note_didattiche WHERE id_tiponota = tipo AND alunno = $stid AND anno = {$_SESSION['__current_year__']->get_ID()} ".$par_tot." GROUP BY tipo, rb_tipi_note_didattiche.descrizione ORDER BY tipo DESC";
try{
	$res_note_did = $db->executeQuery($sel_note_nd);
	$res_tipi_did = $db->executeQuery($sel_tipi_nd);
} catch (MySQLException $ex){
	$ex->redirect();
}
$note_didattiche = array();
$note_didattiche['count'] = 0;
while ($row = $res_note_did->fetch_assoc()) {
	$note_didattiche['count'] += $row['count'];
	$note_didattiche['data'][$row['tipo']] = $row;
}

/*
 * medie voto
 */
$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE id_materia = rb_scrutini.materia AND id_materia <> 40 AND classe = {$_SESSION['__classe__']->get_ID()} ".$scr_par." AND anno = {$anno} AND id_materia > 2 AND tipologia_scuola = {$ordine_scuola} GROUP BY rb_materie.id_materia, rb_materie.materia ORDER BY rb_materie.id_materia";
try {
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex) {
	$ex->redirect();
}
if ($res_materie->num_rows < 1) {
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie WHERE pagella = 1 AND id_materia > 2 AND tipologia_scuola = {$ordine_scuola}";
	if($musicale != "1"){
		$sel_materie .= " AND id_materia <> 13 ";
	}
	$sel_materie .= "ORDER BY id_materia";
	$res_materie = $db->executeQuery($sel_materie);
}
$materie = array();
while($materia = $res_materie->fetch_assoc()){
	if($materia['materia'] == "Scienze motorie")
		$materia['materia'] = "Smotorie";
	$materie[$materia['id_materia']] = array("id" => $materia['id_materia'], "materia" => $materia['materia'], "media" => 0);
}
$sel_voti = "SELECT ROUND(AVG(voto), 2) AS voto, materia, alunno FROM rb_voti WHERE alunno = {$stid} AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time GROUP BY materia, alunno ORDER BY alunno, materia ";
try{
	$res_voti = $db->executeQuery($sel_voti);
} catch (MySQLException $ex){
	$ex->redirect();
}
$sum = 0;
$subj_count = 0;
$media = "Non disponibile";
while ($r = $res_voti->fetch_assoc()) {
	$avg = $r['voto'];
	if ($r['materia'] == 26 || $r['materia'] == 30) {
		$voti_rel = RBUtilities::getReligionGrades();
		$avg = $voti_rel[RBUtilities::convertReligionGrade($avg)];
	}
	$materie[$r['materia']]['media'] = $avg;
	$sum += $r['voto'];
	if ($r['voto'] > 0) {
		$subj_count++;
	}
}
if ($sum > 0) {
	$media = round($sum / $subj_count, 2);
}

$drawer_label = "Scheda riepilogativa di ".$alunno['cognome']." ".$alunno['nome'].$label;

$label_notes_dis = "Nessuna nota presente";
if ($res_note->num_rows > 0) {
	$label_notes_dis = "Sono presenti ".$res_note->num_rows." note";
}

$label_notes_did = "Nessuna nota presente";
if ($note_didattiche['count'] > 0) {
	$label_notes_did = "Sono presenti ".$note_didattiche['count']." note";
}

setlocale(LC_TIME, "it_IT.utf8");

include "scheda_alunno.html.php";
