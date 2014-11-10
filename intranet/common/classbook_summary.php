<?php

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if (isset($_REQUEST['q'])) {
	$q = $_REQUEST['q'];
}
else {
	$q = 0;
}
switch($q){
	case 0:
		$qregistri = "AND data <= NOW()";
		$label = "Riepilogo assenze totali al ".date("d/m/Y");
		break;
	case 1:
		$min = (date("Y-m-d") > $fine_q) ? $fine_q : date("Y-m-d");
		$qregistri = "AND data <= '{$min}'";
		$label = "Riepilogo assenze primo quadrimestre";
		break;
	case 2:
		$qregistri = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = "Riepilogo assenze secondo quadrimestre";
}

$idclasse = $_SESSION['__classe__']->get_ID();

// calcolo del totale monte ore e numero di giorni di lezione della classe
$sel_totali = "SELECT SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN SEC_TO_TIME(TIME_TO_SEC(uscita) -3600) ELSE uscita END), ingresso)))) AS ore, SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN SEC_TO_TIME(TIME_TO_SEC(uscita) -3600) ELSE uscita END), ingresso))))/4) AS limite_ore, COUNT(data) AS giorni, FLOOR(COUNT(data)/4) AS limite_giorni FROM rb_reg_classi WHERE id_classe = ".$idclasse." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." $qregistri";
$res_totali = $db->execute($sel_totali);
$totale = $res_totali->fetch_assoc();

// calcolo dati alunno
$sel_assenze_alunni = "SELECT SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso)))) AS ore, (".$totale['ore']." - SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso))))) AS ore_assenza, COUNT(rb_reg_alunni.ingresso) AS giorni FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE attivo = '1' AND rb_reg_classi.id_classe = ".$idclasse." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_alunni.id_alunno = rb_reg_alunni.id_alunno AND rb_alunni.id_alunno = {$student} $qregistri ";
$res_assenze_alunni = $db->execute($sel_assenze_alunni);
$al = $res_assenze_alunni->fetch_assoc();

/*
 * elenco assenze
 */
$sel_assenze = "SELECT data FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND data <= NOW() AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND id_alunno = $student ";
$res_assenze = $db->executeQuery($sel_assenze);
/*
 * elenco ritardi
 */
$sel_ritardi = "SELECT data, rb_reg_alunni.ingresso AS ingresso FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND rb_reg_alunni.ingresso > rb_reg_classi.ingresso AND id_alunno = $student ";
$res_ritardi = $db->executeQuery($sel_ritardi);
/*
 * elenco uscite anticipate
 */
$sel_uscite = "SELECT data, rb_reg_alunni.uscita AS uscita FROM rb_reg_classi, rb_reg_alunni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_reg_classi.id_classe = {$_SESSION['__classe__']->get_ID()} AND rb_reg_alunni.uscita < rb_reg_classi.uscita AND id_alunno = $student ";
$res_uscite = $db->executeQuery($sel_uscite);
