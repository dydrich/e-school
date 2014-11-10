<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

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
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(!isset($_SESSION['holydays'])){
	$_SESSION['holydays'] = $school_year->getHolydays();
}
$holydays = $_SESSION['holydays'];

$today = date("Y-m-d");
$current_day = null;
/*
 * registro di oggi?
 */
$is_today = false;
if($_REQUEST['data'] == $today){
	$is_today = true;
	$current_day = $today;
}
else{
	$current_day = $_REQUEST['data'];
}

$sel_alunni = "SELECT id_alunno, cognome, nome FROM rb_alunni WHERE rb_alunni.id_classe = ".$_SESSION['__classe__']->get_ID()." AND attivo = '1' ORDER BY cognome, nome";
$res_alunni = $db->execute($sel_alunni);
$alunni = array();
while ($row = $res_alunni->fetch_assoc()){
	$alunni[$row['id_alunno']] = $row['cognome']." ".$row['nome'];
}

$sel_registro = "SELECT id_reg, ingresso, uscita, data FROM rb_reg_classi WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND data = '".$_REQUEST['data']."'";
//print $sel_registro;
$res_registro = $db->execute($sel_registro);
$_SESSION['registro'] = $res_registro->fetch_assoc();

/*
 * recupero i giorni di lezione
*/
$sel_lesson_days = "SELECT data FROM rb_reg_classi WHERE id_classe = {$_SESSION['__classe__']->get_ID()} AND id_anno = {$_SESSION['__current_year__']->get_ID()} AND data <= NOW() ORDER BY data";
$res_lesson_days = $db->execute($sel_lesson_days);
$lesson_days = array();
while($l_day = $res_lesson_days->fetch_assoc()){
	$lesson_days[] = $l_day['data'];
}

/*
 * se oggi e` prevista lezione, confronto con $today
 * else controllo con l'ultimo giorno di lezione fatto
 */
$check_day = $current_day;
if ($res_registro->num_rows < 1 && count($lesson_days) > 0){
	$check_day = $lesson_days[count($lesson_days) - 1];
}
// contatore giorni di lezione, per l'indice odierno
$x_day = 0;
$c_day = null;
foreach ($lesson_days as $d){
	if ($d == $check_day){
		$c_day = $x_day;
		break;
	}
	$x_day++;
}

if ($c_day == 0){
	$data_back = null;
}
else{
	$data_back = $lesson_days[$c_day - 1];
}
if ($c_day == (count($lesson_days) - 1)){
	$data_forward = null;
}
else{
	$data_forward = $lesson_days[$c_day + 1];
}

$summer = $preschool = false;
if($today > $fine_lezioni){
	$summer = true;
}
else if($today < $inizio_lezioni){
	$preschool = true;
}

if ($is_today){
	$ass_ingiustificate = 0;
	
	if ($ordine_scuola == 1){
		// ricerca di assenze non giustificate
		$sel_assenze_ingiustificate = "SELECT data, id_reg, id_alunno FROM rb_reg_classi, rb_reg_alunni WHERE id_reg = id_registro AND rb_reg_alunni.ingresso IS NULL AND giustificata IS NULL AND data < '{$today}' AND rb_reg_classi.id_classe = ".$_SESSION['__classe__']->get_ID()." AND rb_reg_classi.id_anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY data ";
		$res_assenze_ingiustificate = $db->execute($sel_assenze_ingiustificate);
		$assenze_ingiustificate = array();
		while ($row = $res_assenze_ingiustificate->fetch_assoc()){
			if (!isset($assenze_ingiustificate[$row['id_alunno']])){
				$assenze_ingiustificate[$row['id_alunno']] = array();
			}
			$assenze_ingiustificate[$row['id_alunno']][$row['data']] = $row['id_reg']; 
		}
	}
}

/*
 * per il download del registro di classe
 */
$_SESSION['no_file'] = array("referer" => "intranet/teachers/registro_classe/registro_classe.php", "path" => "intranet/teachers/", "relative" => "registro_classe/registro_classe.php");

setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A %d %B %Y", strtotime($_SESSION['registro']['data']));

$navigation_label = "Registro della classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Registro di ". $giorno_str;

if($res_registro->num_rows < 1){
	$drawer_label = "Registro di classe";
	include "no_lessons.php";
}
else{
	include "registro_classe.html.php";
}
