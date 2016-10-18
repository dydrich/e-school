<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";
$_SESSION['__area__'] = "teachers";

$ses_ut = SessionUtils::getInstance($db);
$ses_ut->registerUserConfig($_SESSION['__user__']->getUID(), "__user_config__");

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "registro elettronico ";
$drawer_label = "Home page";

$free_day = false;
$label = "Oggi";
$today = date("Y-m-d");
$tl = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$il = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
if(date("H") > "14"){
	$today = date("Y-m-d", strtotime(date("Y-m-d")." +1 days"));
	$label = "Domani";
}
if($today > $tl){
	$label = "Buone vacanze!";
	$free_day = true;
}
else if ($today < $il){
	$label = "Le lezioni non sono ancora cominciate";
	$free_day = true;
}
else{
	if(date("w", strtotime($today)) == 0){
		$free_day = true;
		$label .= " non c'&egrave; lezione. Buona domenica";
	}
	else{
		/* costruzione del programma del giorno:
		 * step #1: orario generale
		*/
		setlocale(LC_TIME, "it_IT");
		$str_date = strftime("%A", strtotime($today));
		$num_day = date("w", strtotime($today));
		$schedule = array();
		//for($i = 0; $i < 6; $i++)
		//	$schedule[$i] = array();
		if ($_SESSION['__user__']->getSubject() != 27) {
			$sel_sched = "SELECT rb_orario.descrizione, rb_orario.ora, rb_classi.anno_corso AS cl, rb_classi.sezione AS sez, rb_materie.materia AS materia 
						  FROM rb_orario, rb_classi, rb_materie 
						  WHERE docente = " . $_SESSION['__user__']->getUid() . " 
						  AND rb_orario.classe = rb_classi.id_classe 
						  AND rb_orario.materia = rb_materie.id_materia 
						  AND giorno = {$num_day} 
						  AND anno = " . $_SESSION['__current_year__']->get_ID() . " 
						  ORDER BY ora ";
		}
		else {
			$sel_sched = "SELECT rb_orario.descrizione, rb_orario.ora, rb_classi.anno_corso AS cl, rb_classi.sezione AS sez, rb_materie.materia AS materia 
						  FROM rb_orario, rb_classi, rb_materie, rb_orario_sostegno
						  WHERE rb_orario.classe = rb_classi.id_classe 
						  AND rb_orario.materia = rb_materie.id_materia 
						  AND giorno = {$num_day} 
						  AND anno = " . $_SESSION['__current_year__']->get_ID() . " 
						  AND rb_orario.id IN (SELECT id_ora FROM rb_orario_sostegno WHERE id_docente = " . $_SESSION['__user__']->getUid() . ")
						  GROUP BY rb_orario.descrizione, rb_orario.ora, rb_classi.anno_corso, rb_classi.sezione, rb_materie.materia
						  ORDER BY ora ";
		}
		$res_sched = $db->execute($sel_sched);
		if($res_sched->num_rows == 0){
			$free_day = true;
			$label .= " &egrave; il tuo giorno libero";
		}
		else{
			while($sched = $res_sched->fetch_assoc()){
				$schedule[$sched['ora']] = $sched;
				$schedule[$sched['ora']]['att'] = "";
				$schedule[$sched['ora']]['hw'] = "";
			}
			/* step #2: ricerca di attivita' e compiti */
			$sel_act = "SELECT * FROM rb_impegni WHERE data_inizio LIKE '".$today."%' AND docente = ".$_SESSION['__user__']->getUid();
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
}

include "index.html.php";
