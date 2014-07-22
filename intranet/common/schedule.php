<?php

$ore = 8;

$anno = $_SESSION['__current_year__']->get_ID();
$classe = $_SESSION['__classe__']->get_ID();

// array contenente l'orario iniziale delle ore di lezione
$inizio_ore = array("", "8:30", "9:30", "10:30", "11:30", "12:30", "14:30", "15:30", "16:30");

$orario_classe = new Orario();
$sel_orario = "SELECT * FROM rb_orario WHERE classe = {$classe} AND anno = {$anno} ORDER BY giorno, ora";
//print $sel_orario;
$res_orario = $db->execute($sel_orario);
while($ora = $res_orario->fetch_assoc()){
	$a = new OraDiLezione($ora);
	$orario_classe->addHour($a);
	//print $a->getClasse();
}
//print_r($orario_classe);

$schedule_module = $_SESSION['__classe__']->get_modulo_orario();

$materie = array(1 => "---");
$sel_materie = "SELECT * FROM rb_materie WHERE id_materia != 1";
$res_materie = $db->execute($sel_materie);
while($mat = $res_materie->fetch_assoc()){
	$materie[$mat['id_materia']] = $mat['materia'];
}

include "../common/schedule.html.php";
