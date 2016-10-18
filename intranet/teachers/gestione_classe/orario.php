<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

require_once "../reload_class_in_session.php";

$classe = $_SESSION['__classe__']->get_ID();
$anno = $_SESSION['__current_year__']->get_ID();

$schedule_module = $_SESSION['__classe__']->get_modulo_orario();
$ore = 8;
	
// array contenente l'orario iniziale delle ore di lezione
$inizio_ore = $schedule_module->getLessonStartTimes();

$orario_classe = new Orario();
$sel_orario = "SELECT * FROM rb_orario WHERE classe = {$classe} AND anno = $anno ORDER BY giorno, ora";
$res_orario = $db->execute($sel_orario);
while($ora = $res_orario->fetch_assoc()){
	if ($ora['materia'] == "" || $ora['materia'] == 0) {
		$ora['materia'] = 1;
	}
	$a = new OraDiLezione($ora);
	$orario_classe->addHour($a);
	//print $a->getClasse();
}
//print_r($orario_classe);

$sel_cdc = "SELECT id_docente, rb_cdc.id_materia, idpadre FROM rb_cdc, rb_materie WHERE id_classe = $classe AND id_anno = $anno AND rb_cdc.id_materia = rb_materie.id_materia AND id_docente IS NOT NULL ";
$res_cdc = $db->execute($sel_cdc);
$consiglio = array();
while($con = $res_cdc->fetch_assoc()){
    $consiglio[$con['id_materia']] = $con['id_docente'];
}

$materie = array();
$sel_materie = "SELECT * FROM rb_vmaterie_orario WHERE tipologia_scuola = {$_SESSION['__classe__']->getSchoolOrder()} OR tipologia_scuola = 0";
$res_materie = $db->execute($sel_materie);
while($mat = $res_materie->fetch_assoc()){
	//print "<br /><br />New subject<br />";
	$id_doc = 0;
	reset($consiglio);
	while(list($k, $v) = each($consiglio)){
		//print "Confronto k=$k con id_materia=".$mat['id_materia']." e idpadre=".$mat['idpadre']."<br />";
		if(($mat['id_materia'] == $k) || ($mat['idpadre'] == $k)){
			$id_doc = $v;
			break;
		}
	}
	$materie[$mat['id_materia']] = array($mat['materia'], $mat['idpadre'], $id_doc);
}

/*
 * la modifica dell'orario e' permessa solo al coordinatore
 * della classe, per cui verifico e setto un flag: se false,
 * allora si va in sola visualizzazione 
 */
$coordinatore = false;
$support_teacher = false;
if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) {
	$coordinatore = true;
}
if ($_SESSION['__user__']->getSubject() == 27) {
	$support_teacher = true;
}

$navigation_label = "gestione classe";
$drawer_label = "Orario delle lezioni";

include "orario.html.php";
