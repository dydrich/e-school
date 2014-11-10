<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$id_alunno = $_REQUEST['id'];

$assenze = array();
$assenze['09'] = array();
$assenze['10'] = array();
$assenze['11'] = array();
$assenze['12'] = array();
$assenze['01'] = array();
$assenze['02'] = array();
$assenze['03'] = array();
$assenze['04'] = array();
$assenze['05'] = array();
$assenze['06'] = array();
$sel_assenze = "SELECT data FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND data <= NOW() AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NULL AND id_alunno = $id_alunno ";
$res_assenze = $db->execute($sel_assenze);
$tot_assenze = 0;
while($as = $res_assenze->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	array_push($assenze[$mese], $as['data']);
	$tot_assenze++;
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $id_alunno";
$res_alunno = $db->execute($sel_alunno);
$alunno = $res_alunno->fetch_assoc();
$sel_ritardi = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(rb_reg_alunni.ingresso, rb_reg_classi.ingresso))))) AS ore_ritardo, COUNT(rb_reg_alunni.ingresso) AS giorni_ritardo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND data <= NOW() AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND id_alunno = $id_alunno AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso ";
//print $sel_ritardi;
setlocale(LC_ALL, "it_IT.utf8");

$ritardi = array();
$uscite = array();

$mesi = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");

$sel_ritardi = "SELECT data, rb_reg_alunni.ingresso AS ingresso FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso AND id_alunno = $id_alunno ";
$res_ritardi = $db->execute($sel_ritardi);
while($as = $res_ritardi->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	if(!isset($ritardi[$mese]))
		$ritardi[$mese] = array();
	array_push($ritardi[$mese], $as);
}
$sel_uscite = "SELECT data, rb_reg_alunni.uscita AS uscita FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_alunni.uscita < rb_reg_classi.uscita AND id_alunno = $id_alunno ";
$res_uscite = $db->execute($sel_uscite);
while($as = $res_uscite->fetch_assoc()){
	$data = explode("-", $as['data']);
	$mese = $data[1];
	if(!isset($uscite[$mese]))
		$uscite[$mese] = array();
	array_push($uscite[$mese], $as);
}
	
$sel_somma_ritardi = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(rb_reg_alunni.ingresso, rb_reg_classi.ingresso))))) AS ore_ritardo, COUNT(rb_reg_alunni.ingresso) AS giorni_ritardo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND id_alunno = $id_alunno AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso ";
$res_somma_ritardi = $db->execute($sel_somma_ritardi);
$somma_ritardi = $res_somma_ritardi->fetch_assoc();
$sel_somma_uscite  = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(rb_reg_classi.uscita, rb_reg_alunni.uscita))))) AS ore_perse, COUNT(rb_reg_alunni.uscita) AS giorni_anticipo FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND id_alunno = $id_alunno AND rb_reg_classi.uscita > rb_reg_alunni.uscita ";
$res_somma_uscite = $db->execute($sel_somma_uscite);
$somma_uscite = $res_somma_uscite->fetch_assoc();

$drawer_label = "Dettaglio assenze ". $alunno['cognome']." ".$alunno['nome'];

include "dettaglio_alunno.html.php";
