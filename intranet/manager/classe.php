<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);
$label = " classe ".$_REQUEST['desc'];

if(!isset($_REQUEST['id'])){
	$_REQUEST['id'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['id'], "__classe__");
}

$cls = $_REQUEST['id'];
if ($_REQUEST['show'] == "cdc") {
	$fields = array("Docente", "Materia");
	$widths = array(35, 60);
	$drawer_label = "Elenco docenti".$label;
	$data = array();
	require_once "../../lib/RBUtilities.php";
	$utilities = RBUtilities::getInstance($db);
	$data = $utilities->getTeachersOfClass($cls);
	include "classe.html.php";
	exit;
}
else if ($_REQUEST['show'] == "alunni") {
	$query = "SELECT id_alunno, cognome, nome, DATE_FORMAT(data_nascita, '%d/%m/%Y') AS sec_f FROM rb_alunni WHERE id_classe = {$cls} AND attivo = '1' ORDER BY cognome, nome";
	$result = $db->execute($query);
	$fields = array("Alunno", "Data di nascita");
	$widths = array(55, 40);
	$drawer_label = "Elenco alunni".$label;
	$data = array();
	while ($row = $result->fetch_assoc()) {
		$data[] = array("id" => $row['id_alunno'], "nome" => $row['cognome']." ".$row['nome'], "sec_f" => array($row['sec_f']));
	}
	include "classe.html.php";
	exit;
}
else if ($_REQUEST['show'] == "orario") {
	$tempo = "";
	$ore = 8;
	if($_REQUEST['tp'] == 0){
		$tempo = "AND ora < 6 ";
		$ore = 5;
	}
	
	// array contenente l'orario iniziale delle ore di lezione
	$inizio_ore = array("", "8:30", "9:30", "10:30", "11:30", "12:30", "14:30", "15:30", "16:30");
	
	$orario_classe = new Orario();
	$sel_orario = "SELECT * FROM rb_orario WHERE classe = {$cls} $tempo AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY giorno, ora";
	//print $sel_orario;
	$res_orario = $db->execute($sel_orario);
	while($ora = $res_orario->fetch_assoc()){
		$a = new OraDiLezione($ora);
		$orario_classe->addHour($a);
		//print $a->getClasse();
	}
	$materie = array();
	$sel_materie = "SELECT * FROM rb_materie WHERE id_materia > 2";
	$res_materie = $db->execute($sel_materie);
	while($mat = $res_materie->fetch_assoc()){
		$materie[$mat['id_materia']] = $mat['materia'];
	}
	
	$drawer_label = "Orario delle lezioni".$label;
	include "orario_classe.html.php";
	exit;
}
