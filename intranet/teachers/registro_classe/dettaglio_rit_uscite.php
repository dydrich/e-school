<?php

require_once "../../../lib/start.php";
require_once __DIR__."/../../../lib/RBUtilities.php";

check_session();
check_permission(DOC_PERM|GEN_PERM|STD_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if ($_SESSION['__user__'] instanceof StudentBean || $_SESSION['__user__'] instanceof SchoolUserBean) {
	$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
}
else if ($_SESSION['__user__'] instanceof ParentBean) {
	$rb = RBUtilities::getInstance($db);
	$st = $rb->loadUserFromUid($_SESSION['__current_son__'], 'student');
	$ordine_scuola = $st->getSchoolOrder();
}
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$id_alunno = $_REQUEST['alunno'];
$quadrimestre = $_REQUEST['q'];
switch($quadrimestre){
	case 0:
		$par_tot = "AND data <= NOW()";
		break;
	case 1:
		$par_tot = "AND DATA <= '".$fine_q."'";
		break;
	case 2:
		$par_tot = "AND (data >= '".$fine_q."' AND data <= NOW()) ";
}

$ritardi = array();
$uscite = array();
$mesi = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");

$sel_ritardi = "SELECT data, rb_reg_alunni.ingresso AS ingresso FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso AND id_alunno = $id_alunno ";
$res_ritardi = $db->executeQuery($sel_ritardi);
while($as = $res_ritardi->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	if(!isset($ritardi[$mese]))
		$ritardi[$mese] = array();
	array_push($ritardi[$mese], $as);
}

$sel_uscite = "SELECT data, rb_reg_alunni.uscita AS uscita FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND rb_reg_alunni.uscita < rb_reg_classi.uscita AND id_alunno = $id_alunno ";
$res_uscite = $db->executeQuery($sel_uscite);
while($as = $res_uscite->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	if(!isset($uscite[$mese]))
		$uscite[$mese] = array();
	array_push($uscite[$mese], $as);
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $id_alunno";
$res_alunno = $db->executeQuery($sel_alunno);
$alunno = $res_alunno->fetch_assoc();

$sel_somma_ritardi = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(rb_reg_alunni.ingresso, rb_reg_classi.ingresso))))) AS ore_ritardo, COUNT(rb_reg_alunni.ingresso) AS giorni_ritardo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso <> '00:00:00' AND id_alunno = $id_alunno AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso ";
$res_somma_ritardi = $db->executeQuery($sel_somma_ritardi);
$somma_ritardi = $res_somma_ritardi->fetch_assoc();
$sel_somma_uscite  = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(CASE WHEN rb_reg_classi.uscita > '13:30:00' THEN (ADDTIME(rb_reg_classi.uscita, '-1:00:00')) ELSE rb_reg_classi.uscita END, rb_reg_alunni.uscita))))) AS ore_perse, COUNT(rb_reg_alunni.uscita) AS giorni_anticipo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND id_alunno = $id_alunno AND rb_reg_classi.uscita > rb_reg_alunni.uscita ";
//print $sel_somma_uscite;
$res_somma_uscite = $db->executeQuery($sel_somma_uscite);
$somma_uscite = $res_somma_uscite->fetch_assoc();
//print $sel_uscite;
setlocale(LC_TIME, "it_IT.utf8");

include "dettaglio_rit_uscite.html.php";
