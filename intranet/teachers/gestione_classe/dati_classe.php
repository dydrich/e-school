<?php

/*
 * nei vari rami dell'if erano inizialmente sottratti al numero degli ammessi i vari non ammessi,
 * maschili e femminili: questa parte e' stata commentata, perche' nel caso di dati relativi al 
 * finale dell'anno precedente il numero totale degli iscritti, dal quale si ricava quello degli ammessi,
 * e' diverso da quello iniziale dell'anno precedente, dal quale correttamente andrebbero sottratti i non ammessi.
 * Verificare la fattibilita' di recuperare i dati dell'anno precedente, anche nel numero totale degli alunni
 */

include "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

//$c_classe = $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$c_classe = $_SESSION['__classe__']->get_ID();

$sel_dati = "SELECT COUNT(*) AS count, sesso FROM alunni WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." GROUP BY sesso";
$res_dati = $db->execute($sel_dati);
$tot_m = $tot_f = $tot = $ammessi = $ammessi_m = $ammessi_f = $non_val = $non_val_m = $non_val_f = $non_amm = $non_amm_m = $non_amm_f = 0;
while($d = $res_dati->fetch_assoc()){
	if($d['sesso'] == "F")
		$tot_f = $d['count'];
	else
		$tot_m = $d['count'];
	$tot += $d['count'];
}

/*
 * estrazione dei ripetenti, usando il flag ripetente nella tabella alunni
 */
$sel_ripetenti = "SELECT COUNT(*) AS count, sesso FROM alunni WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND ripetente = 1 GROUP BY sesso";
$res_ripetenti = $db->execute($sel_ripetenti);
$ripetenti = array();
$ripetenti['F'] = 0;
$ripetenti['M'] = 0;
while($_ripetenti = $res_ripetenti->fetch_assoc()){
	$ripetenti[$_ripetenti['sesso']] = $_ripetenti['count'];
}
$ripetenti['T'] = $ripetenti['F'] + $ripetenti['M'];

/*
 * calcolo ora che genere di dati devo estrarre, basandomi sul periodo dell'anno in cui vengono mostrate le statistiche
 * 1. se esiste la tabella dati_$anno_2q, il suffisso è $anno_2q, vista non_ammessi
 * 2. se esiste la tabella dati_$anno_1q, il suffisso è $anno_1q, vista non_sufficienti
 * 3. altrimenti, il suffisso è ($anno-1)_2q, vista non_ammessi 
 */
$year = substr($_SESSION['__current_year__']->get_data_chiusura(), 6);
$anno = $year;
if(date("Ymd") < $year."0301"){
	$anno = ($year - 1);
}
$db->select_db("information_schema");
$sel_suff = "SELECT table_name FROM tables WHERE table_name LIKE 'dati%' ORDER BY table_name DESC LIMIT 1";
$res_suff = $db->execute($sel_suff);
$dt = $res_suff->fetch_assoc();
$case = 0;
$db->select_db("scuolamediatre_it_db");
// numeri dell'anno precedente
$sel_num = "SELECT count(distinct(id_alunno)) AS cnt, sesso FROM ".$dt['table_name']."  WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." group by sesso";
$res_num = $db->executeQuery($sel_num);
$old_tot = $ammessi = $ammessi_m = $old_tot_m = $ammessi_f = $old_tot_f;
while($num = $res_num->fetch_assoc()){
	if($num['sesso'] == "F")
		$ammessi_f = $old_tot_f = $num['cnt'];
	else
		$ammessi_m = $old_tot_m = $num['cnt'];
	$old_tot += $num['cnt'];
}
$ammessi = $old_tot;

if($dt['table_name'] == "dati_".$anno."_2q"){
	// caso 1
	$case = 1;
	$vista_nc = "non_classificati_".$anno."_2q";
	$vista_na = "non_ammessi_".$anno."_2q";
	$vista_media_sesso = "classifica_classi_sesso_".$anno."_2q";
	$vista_media = "classifica_classi_".$anno."_2q";
	$vista_ammessi_vc = "non_sufficienti_".$anno."_2q";
	$legend = "scrutinio finale anno scolastico ".($anno - 1)."-".$anno;
	$label = "Non ammessi";
	$label_positive = "Ammessi";
	// non ammessi
	$sel_na = "SELECT COUNT(*) AS count, sesso FROM $vista_na WHERE id_classe = '".$c_classe."' GROUP BY sesso";
	$res_na = $db->execute($sel_na);
	if($res_na->num_rows > 0){
		while($na = $res_na->fetch_assoc()){
			if($na['sesso'] == "F"){
				$non_amm_f = $na['count'];
				$ammessi_f -= $na['count'];
			}
			else{
				$non_amm_m = $na['count'];
				$ammessi_m -= $na['count'];
			}
			$non_amm += $na['count'];
			$ammessi -= $na['count'];
		}
	}
	
	/*
	 * non sufficienti, ammessi con voto di consiglio: media >= 5.6
	 */
	$sel_vc = "SELECT COUNT(*) AS count, sesso FROM $vista_ammessi_vc WHERE id_classe = '".$c_classe."' GROUP BY sesso";
	$res_vc = $db->execute($sel_vc);
	if($res_vc->num_rows > 0){
		while($vc = $res_vc->fetch_assoc()){
			if($vc['sesso'] == "F"){
				$non_suff_f = $vc['count'];
				//$ammessi_f -= $na['count'];
			}
			else{
				$non_suff_m = $vc['count'];
				//$ammessi_m -= $na['count'];
			}
			$non_suff += $vc['count'];
			//$ammessi -= $na['count'];
		}
	}
}
else{
	$case = 1;
	$vista_nc = "non_classificati_".$anno."_1q";
	$vista_na = "non_sufficienti_".$anno."_1q";
	$vista_media_sesso = "classifica_classi_sesso_".$anno."_1q";
	$vista_media = "classifica_classi_".$anno."_1q";
	$legend = "scrutinio primo quadrimestre ".($anno - 1)."-".$anno;
	$label = "Non sufficienti";
	$label_positive = "Sufficienti";
	// non ammessi
	$sel_na = "SELECT COUNT(*) AS count, sesso FROM $vista_na WHERE id_classe = '".$c_classe."' GROUP BY sesso";
	$res_na = $db->execute($sel_na);
	if($res_na->num_rows > 0){
		while($na = $res_na->fetch_assoc()){
			if($na['sesso'] == "F"){
				$non_amm_f = $na['count'];
				$ammessi_f -= $na['count'];
			}
			else{
				$non_amm_m = $na['count'];
				$ammessi_m -= $na['count'];
			}
			$non_amm += $na['count'];
			$ammessi -= $na['count'];
		}
	}
}

$sel_nv = "SELECT COUNT(*) AS count, sesso FROM $vista_nc WHERE id_classe = '".$c_classe."' GROUP BY sesso";
$res_nv = $db->execute($sel_nv);
if($res_nv->num_rows > 0){
	while($v = $res_nv->fetch_assoc()){
		if($v['sesso'] == "F"){
			$non_val_f = $v['count'];
			//$ammessi_f -= $v['count'];
		}
		else{
			$non_val_m = $v['count'];
			//$ammessi_m -= $v['count'];
		}
		$non_val += $v['count'];
		//$ammessi -= $v['count'];
	}
}

$media = $media_m = $media_f = 0;
$sel_md = "SELECT * FROM $vista_media_sesso WHERE id_classe = '".$c_classe."'";
$res_md = $db->execute($sel_md);
while($md = $res_md->fetch_assoc()){
	if($md['sesso'] == "F"){
		$media_f = $md['round'];
	}
	else{
		$media_m = $md['round'];
	}
}

$sel_mt = "SELECT * FROM $vista_media WHERE id_classe = $c_classe";
$res_mt = $db->execute($sel_mt);
$mt = $res_mt->fetch_assoc();
$media = $mt['round'];

include "dati_classe.html.php";

?>