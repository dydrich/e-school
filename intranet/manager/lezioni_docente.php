<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 21/11/13
 * Time: 17.43
 */

require_once '../../lib/start.php';
require_once '../../lib/RBUtilities.php';

check_session();
check_permission(DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

$ordine_scuola = $_SESSION['__school_order__'];
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$q = null;
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

$subj = null;
if (isset($_REQUEST['subj'])){
	$subj = $_REQUEST['subj'];
}

switch($q){
	case 0:
		$int_time = "AND data <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data <= '".$fine_q."'";
		$label = " primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = " secondo quadrimestre";
}

$teacher = $_REQUEST['doc'];
$sel_docente = "SELECT rb_utenti.uid, rb_utenti.nome, rb_utenti.cognome, rb_docenti.*, rb_materie.materia, rb_materie.id_materia FROM rb_docenti, rb_utenti, rb_materie WHERE rb_utenti.uid = rb_docenti.id_docente AND rb_docenti.materia = rb_materie.id_materia AND uid = {$teacher}";
$res_docente = $db->execute($sel_docente);
$docente = $res_docente->fetch_assoc();

$rb = RBUtilities::getInstance($db);

$cls = $rb->loadClassFromClassID($_REQUEST['cls']);
$class = $_REQUEST['cls'];
$teacher_name = $docente['nome']." ".$docente['cognome'];
$class_name = $cls->get_anno().$cls->get_sezione();
$months = array("09", "10", "11", "12", "01", "02", "03", "04", "05");
$italian_months = array("settembre", "ottobre", "novembre", "dicembre", "gennaio", "febbraio", "marzo", "aprile", "maggio");

$sel_lessons = "SELECT id, data, argomento, rb_materie.materia, id_materia, docente, id_classe FROM rb_reg_firme, rb_reg_classi, rb_materie WHERE rb_materie.id_materia = rb_reg_firme.materia AND anno = {$_SESSION['__current_year__']->get_ID()} AND rb_reg_classi.id_reg = id_registro AND rb_reg_classi.id_classe = {$class} AND docente = {$teacher} ".$int_time." ORDER BY data DESC";
$res_lessons = $db->execute($sel_lessons);
$lessons = array();
$start_subj = null;
$x = 0;
while ($less = $res_lessons->fetch_assoc()){
	if ($x == 0){
		$start_subj = $less['id_materia'];
		if ($subj == null){
			$subj = $start_subj;
		}
	}
	$x++;
	if (!isset($lessons[$less['id_materia']])){
		$lessons[$less['id_materia']] = array();
	}
	$lessons[$less['id_materia']]['materia'] = $less['materia'];
	if (!isset($lessons[$less['id_materia']]['lezioni'])){
		$lessons[$less['id_materia']]['lezioni'] = array();
	}
	$lessons[$less['id_materia']]['lezioni'][$less['id']] = $less;
}

$page_label = "Elenco lezioni del docente {$teacher_name}, classe {$class_name} ";

include "lezioni_docente.html.php";
