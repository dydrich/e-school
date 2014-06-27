<?php

/***
 * file: reg_orario.php
 * 
 * permette al docente di firmare il registro per le ore nelle quali ha fatto lezione,
 * indicando anche la materia e l'argomento svolto 
 * 
 */

require_once "../../../lib/start.php";
require_once "../../../lib/ScheduleModule.php";

check_session(FAKE_WINDOW);
check_permission(DOC_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$sel_dati = "SELECT ingresso, uscita, data FROM rb_reg_classi WHERE id_reg = ".$_REQUEST['id_reg'];
$res_dati = $db->executeQuery($sel_dati);
$dati = $res_dati->fetch_assoc();

/*
 * calcolo della prima e ultima ora, in caso di ingressi ritardati o uscite anticipate
 */
$schedule_module = $_SESSION['__classe__']->get_modulo_orario();
$d = date("w", strtotime($dati['data']));
$day = $schedule_module->getDay($d);
$starts = $day->getLessonsStartTime();
list($h, $m, $s) = explode(":", $dati['ingresso']);
$ingresso = new RBTime($h, $m, $s);
list($h, $m, $s) = explode(":", $dati['uscita']);
$uscita = new RBTime($h, $m, $s);

$hours = count($starts);
$prima_ora = 1;
$ultima_ora = $hours;

for($i = 1; $i < $hours; $i++){
	if ($ingresso->compare($starts[$i]) == -1){
		$prima_ora = $i -1;
		break;
	}
}

for($i = $hours; $i > 0; $i--){
	if ($uscita->compare($starts[$i]) == 1){
		$ultima_ora = $i;
		break;
	}
}
/*
 * provvisorio per correggere errore nella visualizzazione delle
 * ore di firma in caso di ingressi particolari, come l'11-13
 * del primo giorno di scuola
 */
$prima_ora = 1;
$dur_time = new RBTime(0, 0, 0);
$dur_time->setTime($uscita->getTime() - $ingresso->getTime());
$min = $day->getHourDuration();
$h = $dur_time->getTime() / $min->getTime();
$ultima_ora = $h;

/* 
 * array di firme, argomenti e id ore
 */
$sel_firme = "SELECT * FROM rb_reg_firme WHERE id_registro = ".$_REQUEST['id_reg']." ORDER BY ora";
//print $sel_firme;
$res_firme = $db->executeQuery($sel_firme);
$firme = array();
$argomenti = array();
$ids = array();
$docenti = array();
$_materie = array();
for($x = $prima_ora; $x <= $ultima_ora; $x++){
	$firme[$x] = array();
	$argomenti[$x] = array();
	$ids[$x] = $docenti[$x] = $_materie[$x] = 0;
}
if($res_firme->num_rows > 0){
	while($sig = $res_firme->fetch_assoc()){
		$firme[$sig['ora']] = $sig['firma'];
		$argomenti[$sig['ora']] = $sig['argomento'];
		$ids[$sig['ora']] = $sig['id'];
		$docenti[$sig['ora']] = $sig['docente'];
		$_materie[$sig['ora']] = $sig['materia'];
		$cdocenti[$sig['ora']] = $sig['docente_compresenza'];
		$c_materie[$sig['ora']] = $sig['materia_compresenza'];
	}
}
//print_r($firme);

setlocale(LC_TIME, "it_IT");
$giorno_str = utf8_encode(strftime("%A", strtotime($dati['data'])));

/*
 * estrazione delle materie insegnate
 * prima estraggo le materie base insegnate nella classe
 * poi in base alla materia estratta verifico se necessario
 * estrarre le materie figlie o figlie di figlie (nel caso di lettere)
 */
$materie = array();
if ($_SESSION['__user__']->getSubject() == 27) {
	$materie[] = array("id_materia" => 27, "has_sons" => 0, "materia" => "Sostegno");
}
else {
	$materia_base = array();
	$sel_materia = "SELECT rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia FROM rb_docenti, rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_classe = ".$_SESSION['__classe__']->get_ID()." AND rb_docenti.id_docente = ".$_SESSION['__user__']->getUid()." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." GROUP BY rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia ORDER BY rb_materie.id_materia";
	//print $sel_materia;
	$res_materia = $db->executeQuery($sel_materia);
	while($record = $res_materia->fetch_assoc()){
		array_push($materia_base, $record);
	}
	//print_r($materia_base);
	/*
	 * primo caso: una sola materia insegnata nella classe:
	 * in questo caso, una seconda estrazione e' necessaria solo 
	 * per Matematica e Storia-Geografia.
	 */
	if($res_materia->num_rows < 2){
		if(!$materia_base[0]['has_sons']){
			array_push($materie, $materia_base[0]);
		}
		else{
			$sel_materie = "SELECT rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia FROM rb_materie WHERE idpadre = ".$materia_base[0]['id_materia'];
			$res_materie = $db->executeQuery($sel_materie);
		}
	}
	/*
	 * secondo caso: insegnante di lettere della classe
	 * sono possibili due varianti: italiano + storia e geografia;
	 * italiano + storia e geografia + approfondimento
	 */
	else{
		foreach($materia_base as $m){
			if($m['has_sons'] == 0){
				// approfondimento e italiano: inserisco nella tabella
				array_push($materie, $m);
			}
			else{
				$sel_materie = "SELECT rb_materie.id_materia, rb_materie.has_sons, rb_materie.materia FROM rb_materie WHERE idpadre = ".$m['id_materia'];
				$res_materie = $db->executeQuery($sel_materie);
			}
		}
	}
	if(isset($res_materie)){
		while($mt = $res_materie->fetch_assoc()){
			array_push($materie, $mt);
		}
	}
}
	
//print($sel_materie);

include "reg_orario.html.php";

?>