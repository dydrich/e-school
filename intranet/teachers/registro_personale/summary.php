<?php

/*
 * file di riepilogo medie per alunno
 * Si visualizza solo nel caso di insegnanti che abbiano piu' di una materia valutata
 * nella stessa classe: e' quindi riservato a insegnanti di Lettere e Scienze Matematiche
 */

require_once "../../../lib/start.php";

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

if(isset($_REQUEST['q']))
	$q = $_REQUEST['q'];
else{
	if(date("Y-m-d") > $fine_q){
		$q = 2;
	}
	else{
		$q = 1;
	}
}
	
switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '".$fine_q."' AND data_voto <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE rb_alunni.id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}

$mat = $_SESSION['__user__']->getSubject();

$first_column = $other_column = 0;
$num_subject = count($_SESSION['__subjects__']);
if($num_subject == 2){
	$first_column = 60;
	$other_column = 20;
}
else if($num_subject == 3){
	$first_column = 49;
	$other_column = 17;
}
else if($num_subject == 4){
	$first_column = 40;
	$other_column = 15;
}

$navigation_label = "Registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Riepilogo medie per materia". $label;

include "summary.html.php";
