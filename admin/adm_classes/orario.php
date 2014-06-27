<?php

/*
 * inserimento e modifica orario di classe
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = getAdminLevel($_SESSION['__user__']);

$ore = 8;

$classe = $_REQUEST['cls'];
$anno = $_SESSION['__current_year__']->get_ID();

$offset = 0;
if(isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}

$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE id_classe = {$classe} AND sede = id_sede";
try{
	$res_classe = $db->executeQuery($sel_classe);
} catch (MySQLException $ex){
	$ex->alert();
}
$myclass = $res_classe->fetch_assoc();

$subject_params = " WHERE (tipologia_scuola = {$myclass['ordine_di_scuola']} OR tipologia_scuola = 0)";
	
// array contenente l'orario iniziale delle ore di lezione
$inizio_ore = array("", "8:30", "9:30", "10:30", "11:30", "12:30", "14:30", "15:30", "16:30");

$orario_classe = new Orario();
$sel_orario = "SELECT * FROM rb_orario WHERE classe = ".$classe." AND anno = $anno ORDER BY giorno, ora";
//print $sel_orario;
$res_orario = $db->execute($sel_orario);
while($ora = $res_orario->fetch_assoc()){
	$a = new OraDiLezione($ora);
	$orario_classe->addHour($a);
	//print $a->getClasse();
}

$ore = $db->executeCount("SELECT MAX(ora) FROM rb_orario WHERE classe = ".$classe." AND anno = $anno");

$sel_cdc = "SELECT id_docente, rb_cdc.id_materia, idpadre FROM rb_cdc, rb_materie WHERE id_classe = $classe AND id_anno = $anno AND rb_cdc.id_materia = rb_materie.id_materia AND id_docente IS NOT NULL ";
$res_cdc = $db->execute($sel_cdc);
$consiglio = array();
while($con = $res_cdc->fetch_assoc()){
    $consiglio[$con['id_materia']] = $con['id_docente'];
}

$materie = array();
$sel_materie = "SELECT * FROM rb_vmaterie_orario {$subject_params}";
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

$navigation_label = "Area amministrazione: gestione classi";

include "orario.html.php";