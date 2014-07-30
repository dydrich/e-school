<?php

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$ore = 8;

// array contenente l'orario iniziale delle ore di lezione
$inizio_ore = array("", "8:30", "9:30", "10:30", "11:30", "12:30", "14:30", "15:30", "16:30");

/*
 * readonly: permesso di modifica dell'orario
 * true se si tratta di un supplente
 */
$readonly = false;

/*
 * estrazione dell'orario del docente, memorizzato in un array multidimensionale
 * al primo livello l'indice e' il giorno (stringa di 3 lettere)
 * al secondo livello l'indice e' l'ora (1,2,3..)
 */
$orario_doc = array();
$sel_orario = "SELECT rb_orario.*, rb_classi.anno_corso AS cl, rb_classi.sezione, rb_materie.materia AS mat FROM rb_orario, rb_materie, rb_classi WHERE rb_orario.classe = rb_classi.id_classe AND rb_orario.materia = rb_materie.id_materia AND docente = ".$_SESSION['__user__']->getUid(true)." AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY giorno, ora";
//print $sel_orario;
$res_orario = $db->execute($sel_orario);
$giorno = "";
$day = null;
while($ora = $res_orario->fetch_assoc()){
	if($giorno != $ora['giorno']){
		if($giorno != ""){
			$orario_doc[$giorno] = array();
			$orario_doc[$giorno] = $day;
			$day = array();
		}
		else{
			$day = array();
		}
	}
	$day[$ora['ora']] = $ora;
	$giorno = $ora['giorno'];
}
$orario_doc[$giorno] = array();
$orario_doc[$giorno] = $day;
$_SESSION['personal_schedule'] = $orario_doc;

/*
 * estrazione delle materie del docente e memorizzazione in un array con indice l'id materia
 */
if ($_SESSION['__user__']->isSupplyTeacher()) {
	$sel_mat_docente = "SELECT rb_materie.* FROM rb_docenti, rb_materie WHERE rb_docenti.materia = rb_materie.id_materia AND id_docente = ".$_SESSION['__user__']->getUid(true);
	$readonly = true;
}
else {
	$sel_mat_docente = "SELECT rb_materie.* FROM rb_docenti, rb_materie WHERE rb_docenti.materia = rb_materie.id_materia AND id_docente = ".$_SESSION['__user__']->getUid();
	$readonly = false;
}

$res_mat_docente = $db->execute($sel_mat_docente);
$mat_doc = $res_mat_docente->fetch_assoc();
$res_mat_docente->data_seek(0);
$materie = array();
if($mat_doc['id_materia'] == 12){
	$sel_materie = "SELECT * FROM rb_materie WHERE id_materia = 12 OR idpadre = 12 OR idpadre = 2";
	//print $sel_materie;
	$res_materie = $db->execute($sel_materie);
}
else if($mat_doc['id_materia'] == 7){
	$sel_materie = "SELECT * FROM rb_materie WHERE id_materia = 7 OR idpadre = 7";
	//print $sel_materie;
	$res_materie = $db->execute($sel_materie);
}
else if ($mat_doc['id_materia'] == 39){
	$sel_materie = "SELECT * FROM rb_materie WHERE idpadre = 39";
	$res_materie = $db->execute($sel_materie);
}
else{
	$res_materie = $res_mat_docente;
}
$x = 0;
while($mat = $res_materie->fetch_assoc()){
	$materie[$x] = array($mat['id_materia'], $mat['materia']);
	$x++;
}

/*
 * estrazione delle classi del docente e memorizzazione in un array con indice l'id classe
 */
if ($_SESSION['__user__']->getSubject() != 27 && $_SESSION['__user__']->getSubject() != 41){
	$sel_classi = "SELECT rb_classi.id_classe, rb_classi.anno_corso, rb_classi.sezione FROM rb_classi, rb_cdc WHERE rb_classi.id_classe = rb_cdc.id_classe AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid($readonly)." AND rb_cdc.id_anno = ".$_SESSION['__current_year__']->get_ID()." GROUP BY rb_classi.id_classe, rb_classi.anno_corso, rb_classi.sezione ORDER BY rb_classi.sezione, rb_classi.anno_corso";
}
else{
	$sel_classi = "SELECT rb_classi.id_classe, rb_classi.anno_corso, rb_classi.sezione FROM rb_classi, rb_assegnazione_sostegno WHERE rb_classi.id_classe = classe AND docente = ".$_SESSION['__user__']->getUid($readonly)." AND anno = ".$_SESSION['__current_year__']->get_ID()." GROUP BY rb_classi.id_classe, rb_classi.anno_corso, rb_classi.sezione ORDER BY rb_classi.sezione, rb_classi.anno_corso";
} 
$res_classi = $db->execute($sel_classi);
$classi = array();
$x = 0;
while($cls = $res_classi->fetch_assoc()){
	$classi[$x] = array($cls['id_classe'], $cls['anno_corso'].$cls['sezione']);
	$x++;
}

$navigation_label = "Registro elettronico - Gestione orario personale";

include "schedule.html.php";
