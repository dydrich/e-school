<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);
if(!isset($_SESSION['__user__'])){
	echo "ahi ahi....";
}
//check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "students";

if(!isset($_SESSION['__classe__'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_SESSION['__user__']->getUid(), "__classe__");
}

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$sel_voti = "SELECT voto, descrizione, data_voto, argomento, rb_materie.materia, rb_utenti.nome, rb_utenti.cognome FROM rb_voti, rb_materie, rb_utenti WHERE alunno = ".$_SESSION['__user__']->getUid()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND rb_materie.id_materia = rb_voti.materia AND docente = rb_utenti.uid AND privato = 0 ORDER BY data_voto DESC LIMIT 3";
$res_voti = $db->execute($sel_voti);

$free_day = false;
$label = "Oggi";
$today = date("Y-m-d");
if(date("H") > "14"){
	$today = date("Y-m-d", strtotime(date("Y-m-d")." +1 days"));
	$label = "Domani";
}
//print $today;
if($today > $fine_lezioni || $today < $inizio_lezioni){
	$free_day = true;
	$label = " Buone vacanze. ";
}
else if(date("w", strtotime($today)) == 0){
	$free_day = true;
	$label .= " non c'&egrave; lezione. Buona domenica";
} 
else{
	/* costruzione del programma del giorno:
	 * step #1: orario generale
	*/
	setlocale(LC_TIME, "it_IT.utf8");
	$str_date = strftime("%A", strtotime($today));
	$day = date("N", strtotime($today));
	$schedule = array();
	//for($i = 0; $i < 6; $i++)
	//	$schedule[$i] = array();
	$sel_sched = "SELECT rb_orario.descrizione, rb_orario.ora, rb_classi.anno_corso AS cl, rb_classi.sezione AS sez, rb_materie.materia AS materia FROM rb_orario, rb_classi, rb_materie WHERE rb_classi.id_classe = ".$_SESSION['__classe__']->get_ID()." AND rb_orario.classe = rb_classi.id_classe AND rb_orario.materia = rb_materie.id_materia AND giorno = {$day} AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY ora ";
	$res_sched = $db->execute($sel_sched);
	for ($i = 0; $i < 8; $i++) {
		if (!isset($schedule[$i])) {
			$schedule[$i] = array();
			$schedule[$i]['att'] = "";
			$schedule[$i]['hw'] = "";
		}
	}
	if($res_sched->num_rows == 0){
		$free_day = true;
		$label .= " non c'&egrave; lezione";
	}
	else{
		while($sched = $res_sched->fetch_assoc()){
			$schedule[$sched['ora']] = $sched;
			$schedule[$sched['ora']]['att'] = "";
			$schedule[$sched['ora']]['hw'] = "";
		}

		/* step #2: ricerca di attivita' e compiti */
		$sel_act = "SELECT * FROM rb_impegni WHERE data_inizio LIKE '".$today."%' AND classe = ".$_SESSION['__classe__']->get_ID();
		$res_act = $db->execute($sel_act);
		$index = 0;
		if($res_act->num_rows > 0){
			while($act = $res_act->fetch_assoc()){
				list($d, $h) = explode(" ", $act['data_inizio']);
				switch(substr($h, 0, 2)){
					case "08":
						$index = 1;
						break;
					case "09":
						$index = 2;
						break;
					case "10":
						$index = 3;
						break;
					case "11":
						$index = 4;
						break;
					case "12":
						$index = 5;
						break;
				}
				if($act['tipo'] == 1)
				$schedule[$index]['att'] = $act['descrizione'];
			}
		}
	}
}

$navigation_label = "scuola secondaria";
$drawer_label = "Home page";

include "index.html.php";
