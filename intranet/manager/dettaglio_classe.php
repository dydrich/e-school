<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area ".$_SESSION['__role__'];

if(isset($_REQUEST['q']))
	$q = $_REQUEST['q'];
else
	$q = 0;

switch($q){
	case 0:
		$par_tot = "AND data < NOW()";
		break;
	case 1:
		$par_tot = "AND data <= '".format_date($_SESSION['__current_year__']->get_fine_quadrimestre(), IT_DATE_STYLE, SQL_DATE_STYLE, "-")."'";
		break;
	case 2:
		$par_tot = "AND (data >= '".format_date($_SESSION['__current_year__']->get_fine_quadrimestre(), IT_DATE_STYLE, SQL_DATE_STYLE, "-")."' AND data <= NOW()) ";
}

// estraggo i dati della classe
$sel_classe = "SELECT rb_classi.*, rb_sedi.nome AS sede_des FROM rb_classi, rb_sedi WHERE rb_classi.id_classe = ".$_REQUEST['id']." AND rb_classi.sede = rb_sedi.id_sede";
$res_classe = $db->execute($sel_classe);
$current_class = new Classe($res_classe->fetch_assoc(), $db);

/*
$sel_totali = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN SEC_TO_TIME(TIME_TO_SEC(uscita) -3600) ELSE uscita END), ingresso))))) AS ore, SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN '16:30:00' ELSE uscita END), ingresso))))/4) AS limite_ore, COUNT(data) AS giorni, FLOOR(COUNT(data)/4) AS limite_giorni FROM rb_reg_classi WHERE id_classe = ".$_REQUEST['id']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot";
//print $sel_totali;
$res_totali = $db->execute($sel_totali);
$totali = $res_totali->fetch_assoc();
list($ore, $minuti, $secondi) = explode(":", $totali['ore']);
list($ore2, $minuti2, $secondi2) = explode(":", $totali['limite_ore']);
$sel_assenze_alunni = "SELECT rb_alunni.id_alunno, cognome, nome, SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso))))) AS ore, TIMEDIFF('".$totali['ore']."', SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso)))))) AS ore_assenza, COUNT(rb_reg_alunni.ingresso) AS giorni FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE rb_reg_classi.id_classe = ".$_REQUEST['id']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_alunni.id_alunno = rb_reg_alunni.id_alunno GROUP BY rb_alunni.id_alunno, cognome, nome ORDER BY cognome, nome";
$res_assenze_alunni = $db->execute($sel_assenze_alunni);
//print $sel_assenze_alunni;
*/

$sel_totali = "SELECT SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN SEC_TO_TIME(TIME_TO_SEC(uscita) -3600) ELSE uscita END), ingresso)))) AS ore, SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN SEC_TO_TIME(TIME_TO_SEC(uscita) -3600) ELSE uscita END), ingresso))))/4) AS limite_ore, COUNT(data) AS giorni, FLOOR(COUNT(data)/4) AS limite_giorni FROM rb_reg_classi WHERE id_classe = ".$_REQUEST['id']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot";
//print $sel_totali;
$res_totali = $db->execute($sel_totali);
$totali = $res_totali->fetch_assoc();
//list($ore, $minuti, $secondi) = explode(":", $totali['ore']);
$secondi = $totali['ore']%60;
$tot_min = ($totali['ore'] - $secondi) / 60;
list($ore, $minuti) = explode(":", minutes2hours($tot_min, ""));
$tot_ore = "$ore:$minuti:$secondi";
list($ore2, $minuti2, $secondi2) = explode(":", $totali['limite_ore']);
$sel_assenze_alunni = "SELECT rb_alunni.id_alunno, cognome, nome, SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso)))) AS ore, (".$totali['ore']." - SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso))))) AS ore_assenza, COUNT(rb_reg_alunni.ingresso) AS giorni FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE attivo = '1' AND rb_reg_classi.id_classe = ".$_REQUEST['id']." AND rb_reg_classi.id_classe = rb_alunni.id_classe AND id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_alunni.id_alunno = rb_reg_alunni.id_alunno GROUP BY rb_alunni.id_alunno, cognome, nome ORDER BY cognome, nome";
//print $sel_assenze_alunni;
$res_assenze_alunni = $db->execute($sel_assenze_alunni);

include "dettaglio_classe.html.php";
