<?php

require_once "../../lib/start.php";
require_once "../../lib/RBTime.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$classes_table = "rb_classi";
if ($_SESSION['__school_order__']){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];
}

// default: usa la cache
$nocache = false;
$xml = null;
$ask = false;
$path = $_SESSION['__config__']['html_root']."/tmp/absences_summary_".$_SESSION['__current_year__']->get_ID().".xml";

// richiesta esplicita di non usare i dati in cache
if(isset($_REQUEST['nocache']) && $_REQUEST['nocache'] == 1){
	$nocache = true;
	//print "true su REQUEST";
}
else{
	// nessuna richiesta esplicita: controllo se i dati sono aggiornati
	if(file_exists($path)){
		$nocache = false;
	}
	if(isset($_SESSION['last_stats_absences_manager']) && date("Y-m-d") > $_SESSION['last_stats_absences_manager']){
		$nocache = true;
		//print "true su controllo data";
	}
}

if($nocache){
	$over_25 = array();
	$between_20_25 = array();
	$classes_summary = array();
	
	$sel_classi = "SELECT * FROM {$classes_table} ORDER BY sezione, anno_corso";
	$res_classi = $db->execute($sel_classi);
	while($classe = $res_classi->fetch_assoc()){
		/*
		 * estrazione degli orari di ingresso e uscita della classe, per il calcolo del monte ore totale
		 */
		$cod_class = $classe['anno_corso'].$classe['sezione'];
		$classes_summary[$cod_class] = array();
		$sel_totali = "SELECT SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN SEC_TO_TIME(TIME_TO_SEC(uscita) -3600) ELSE uscita END), ingresso)))) AS ore, SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN uscita > '13:30:00' THEN SEC_TO_TIME(TIME_TO_SEC(uscita) -3600) ELSE uscita END), ingresso))))/4) AS limite_ore, COUNT(data) AS giorni, FLOOR(COUNT(data)/4) AS limite_giorni FROM rb_reg_classi WHERE id_classe = ".$classe['id_classe']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND data <= NOW()";		
		//echo $sel_totali;
		$res_totali = $db->execute($sel_totali);
		$totali = $res_totali->fetch_assoc();
		//list($ore, $minuti, $secondi) = explode(":", $totali['ore']);
		$secondi = $totali['ore']%60;
		$tot_min = ($totali['ore'] - $secondi) / 60;
		list($ore, $minuti) = explode(":", minutes2hours($tot_min, ""));
		$tot_ore = "$ore:$minuti:$secondi";
		list($ore2, $minuti2, $secondi2) = explode(":", $totali['limite_ore']);
		
		$classes_summary[$cod_class]['ore_lezione'] = $totali['ore'];
		$classes_summary[$cod_class]['giorni_lezione'] = $totali['giorni'];
		$classes_summary[$cod_class]['ore_limite'] = $totali['limite_ore'];
		$classes_summary[$cod_class]['giorni_limite'] = $totali['limite_giorni'];
		$classes_summary[$cod_class]['non_validati'] = 0;
		$classes_summary[$cod_class]['a_rischio'] = 0;
		$classes_summary[$cod_class]['id'] = $classe['id_classe'];
		
		/*
		 * calcolo dei valori per alunno: registro solo quelli che presentano dati notevoli (> 25 || 20 < x < 25)
		 */
		//$sel_assenze_alunni = "SELECT rb_alunni.id_alunno, cognome, nome, SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso)))) AS ore, (".$totali['ore']." - SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita ELSE '13:30:00' END), rb_reg_alunni.ingresso))))) AS ore_assenza, COUNT(rb_reg_alunni.ingresso) AS giorni FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE attivo = '1' AND rb_reg_classi.id_classe = ".$classe['id_classe']." AND rb_reg_classi.id_classe = rb_alunni.id_classe AND id_anno = ".$_SESSION['__current_year__']->get_ID()." $par_tot AND id_reg = id_registro AND rb_reg_alunni.ingresso IS NOT NULL AND rb_alunni.id_alunno = rb_reg_alunni.id_alunno GROUP BY rb_alunni.id_alunno, cognome, nome ORDER BY cognome, nome";
		$sel_assenze_alunni = "SELECT rb_alunni.id_alunno, cognome, nome, SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita WHEN rb_reg_alunni.uscita IS NULL THEN 0 ELSE '13:30:00' END), CASE WHEN( rb_reg_alunni.ingresso IS NOT NULL) THEN rb_reg_alunni.ingresso ELSE 0 END)))) AS ore,";
		$sel_assenze_alunni .= "(".$totali['ore']." - SUM(TIME_TO_SEC((TIMEDIFF((CASE WHEN (rb_reg_alunni.uscita > '14:31:00') THEN SEC_TO_TIME(TIME_TO_SEC(rb_reg_alunni.uscita) -3600) WHEN rb_reg_alunni.uscita < '13:30:00' THEN rb_reg_alunni.uscita WHEN rb_reg_alunni.uscita IS NULL THEN 0 ELSE '13:30:00' END), CASE WHEN (rb_reg_alunni.ingresso IS NOT NULL) THEN rb_reg_alunni.ingresso ELSE 0 END))))) AS ore_assenza, ";
		$sel_assenze_alunni .= "COUNT(rb_reg_alunni.ingresso) AS giorni FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE attivo = '1' AND rb_reg_classi.id_classe = ".$classe['id_classe']." AND rb_reg_classi.id_classe = rb_alunni.id_classe AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND data <= NOW() AND id_reg = id_registro AND rb_alunni.id_alunno = rb_reg_alunni.id_alunno GROUP BY rb_alunni.id_alunno, cognome, nome ORDER BY cognome, nome";
		
		$res_assenze_alunni = $db->execute($sel_assenze_alunni);	
		while($al = $res_assenze_alunni->fetch_assoc()){
			$assenze = $totali['giorni'] - $al['giorni'];
			$perc_assenze = round((($assenze / $totali['giorni']) * 100), 2);
			/**
			 * calcolo della percentuale oraria di assenze mediante conversione
			 * dei time in secondi
			 */
			// numero totale di ore di lezione
			$tot_hours = $totali['ore'];
			// ore di assenza (in secondi)
			$abs_hours = $al['ore_assenza'];
			$perc_hours = round((($abs_hours / $tot_hours) * 100), 2);
			// formattazione ore assenza
			$abs_sec = $abs_hours%60;
			$t_m = $abs_hours - $abs_sec;
			$t_m /= 60;
			$ore_assenza = minutes2hours($t_m, "-");
			if($perc_hours > 25){
				// alunno con anno attualmente non validato
				array_push($over_25, array("id" => $al['id_alunno'], "alunno" => $al['cognome']." ".$al['nome'], "perc" => $perc_hours, "ore" => $ore_assenza, "giorni_assenza" => $assenze, "classe" => $cod_class));
				$classes_summary[$cod_class]['non_validati']++;
			}
			else if(($perc_hours > 20) && ($perc_hours < 25)){
				// alunno con anno a rischio
				array_push($between_20_25, array("id" => $al['id_alunno'], "alunno" => $al['cognome']." ".$al['nome'], "perc" => $perc_hours, "ore" => $ore_assenza, "giorni_assenza" => $assenze, "classe" => $cod_class));
				$classes_summary[$cod_class]['a_rischio']++;
			}
		}
	}
	// creazione del file xml
	$f = fopen($path, "w");
	fclose($f);
	$doc = new DOMDocument('1.0');
	$doc->formatOutput = true;
	$doc->preserveWhiteSpace = false;
	$doc->formatOutput = true;
	$root = $doc->createElement('root');
	$root = $doc->appendChild($root);
	/*
	 * primo elemento: data creazione statistiche
	 */
	$date = $doc->createElement('data_creazione');
	$date = $root->appendChild($date);
	$date_text = $doc->createTextNode(date("Y-m-d"));
	$date->appendChild($date_text);
	/*
	 * 2 elemento: ora creazione
	 */
	$time = $doc->createElement('ora_creazione');
	$time = $root->appendChild($time);
	$time_text = $doc->createTextNode(date("H:i:s"));
	$time->appendChild($time_text);
	/*
	 * 3 elemento: alunni non validabili
	 */
	$non_validati = $doc->createElement("non_validati");
	$non_validati = $root->appendChild($non_validati);
	foreach($over_25 as $data){
		$alunno = $non_validati->appendChild(new DOMElement("alunno"));
		$alunno->setAttribute("id", $data['id']);
		$d1 = $alunno->appendChild(new DOMElement("nome", $data['alunno']));
		$d2 = $alunno->appendChild(new DOMElement("classe_app", $data['classe']));
		$d3 = $alunno->appendChild(new DOMElement("assenze", $data['giorni_assenza']));
		$d4 = $alunno->appendChild(new DOMElement("ore_assenza", $data['ore']));
		$d5 = $alunno->appendChild(new DOMElement("perc_ore", $data['perc']));
	}
	/*
	 * 4 elemento: alunni a rischio (20 < x < 25)
	 */
	$a_rischio = $doc->createElement("a_rischio");
	$a_rischio = $root->appendChild($a_rischio);
	foreach ($between_20_25 as $data){
		$alunno = $a_rischio->appendChild(new DOMElement("alunno"));
		$alunno->setAttribute("id", $data['id']);
		$d1 = $alunno->appendChild(new DOMElement("nome", $data['alunno']));
		$d2 = $alunno->appendChild(new DOMElement("classe_app", $data['classe']));
		$d3 = $alunno->appendChild(new DOMElement("assenze", $data['giorni_assenza']));
		$d4 = $alunno->appendChild(new DOMElement("ore_assenza", $data['ore']));
		$d5 = $alunno->appendChild(new DOMElement("perc_ore", $data['perc']));
	}
	/*
	 * 5 elemento: riepilogo classi
	 */
	$classi = $doc->createElement("classi");
	$classi = $root->appendChild($classi);
	while(list($class, $data) = each($classes_summary)){
		$classe = $classi->appendChild(new DOMElement("classe"));
		$classe->setAttribute("id", $data['id']);
		$d1 = $classe->appendChild(new DOMElement("giorni_lezione", $data['giorni_lezione']));
		$d2 = $classe->appendChild(new DOMElement("ore_lezione", $data['ore_lezione']));
		$d3 = $classe->appendChild(new DOMElement("giorni_limite", $data['giorni_limite']));
		$d4 = $classe->appendChild(new DOMElement("ore_limite", $data['ore_limite']));
		$d5 = $classe->appendChild(new DOMElement("non_validati", $data['non_validati']));
		$d6 = $classe->appendChild(new DOMElement("a_rischio", $data['a_rischio']));
		$d7 = $classe->appendChild(new DOMElement("nome", $class));
	}	
	$doc->save($path);
	$data_creazione = date("d/m/Y");
	$ora_creazione = date("H:i:s");
	$upd_last_creation = $db->executeUpdate("UPDATE rb_config SET valore = '".date("Y-m-d")."' WHERE variabile = 'last_stats_absences_manager'");
	$_SESSION['last_stats_absences_manager'] = date("Y-m-d");
	//print $upd;
}

$xml = new DOMDocument();
$xml->load($path);
$xml->preserveWhiteSpace = false;
$dompath = new DOMXPath($xml);
$creation_date = $xml->getElementsByTagName('data_creazione')->item(0);
$creation_time = $xml->getElementsByTagName('ora_creazione')->item(0);
$data_creazione = format_date($creation_date->nodeValue, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
$ora_creazione = $creation_time->nodeValue;
$non_validati = $xml->getElementsByTagName('non_validati')->item(0);
$a_rischio = $xml->getElementsByTagName('a_rischio')->item(0);
$classi = $xml->getElementsByTagName('classi')->item(0);

$drawer_label = "Statistiche assenze aggiornate al ".$data_creazione.", ore ".$ora_creazione;

include "registro.html.php";
