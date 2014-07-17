<?php

/*
 * ripetenti.php
 * mostra tutti gli alunni di terza (terze uscenti) per indicare i ripetenti
 * step 1 della procedura di attivazione classi per nuovo anno
 * 23/04/2011
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|AMS_PERM|APS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = getAdminLevel($_SESSION['__user__']);
$navigation_label = "Area amministrazione";

$school_order = $_REQUEST['school_order'];
if ($school_order == 1){
	$sc_label = "scuola secondaria ";
	$term_cls = 3;
	$cl_label = "terze";
}
else {
	$sc_label = "scuola primaria";
	$term_cls = 5;
	$cl_label = "quinte";
}

$sel_alunni = "SELECT id_alunno, cognome, nome, rb_alunni.id_classe, CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS classe, ripetente FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND anno_corso = {$term_cls} AND ordine_di_scuola = {$school_order} ORDER BY sezione, anno_corso, cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunni = array();
$classi = array();
$first = 0;
while($alunno = $res_alunni->fetch_assoc()){
	if($first == 0)
		$first = $alunno['classe'];
	if(!in_array($alunno['classe'], $classi))
		array_push($classi, $alunno['classe']); 
	if(!isset($alunni[$alunno['classe']]))
		$alunni[$alunno['classe']] = array();
	$alunni[$alunno['classe']][$alunno['id_alunno']] = $alunno;
}

include "ripetenti.html.php";