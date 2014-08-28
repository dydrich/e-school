<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

$anno = $_SESSION['__current_year__']->get_ID();
$school = $_SESSION['__school_order__'];

if ($school == 1){
	$sostegno = 27;
}
else if ($school == 2){
	$sostegno = 41;
}

$sel_sos = "SELECT CONCAT_WS(' ', rb_utenti.cognome, rb_utenti.nome) AS docente, uid, classe, CONCAT(anno_corso, sezione) AS d_classe, ore FROM rb_utenti, rb_docenti, rb_classi, rb_assegnazione_sostegno WHERE materia = {$sostegno} AND uid = id_docente AND id_docente = docente AND classe = id_classe AND anno = {$anno} ORDER BY cognome, nome, sezione, anno_corso";
$res_sos = $db->execute($sel_sos);
$sos = array();
while ($row = $res_sos->fetch_assoc()){
	if (!isset($sos[$row['uid']])){
		$sos[$row['uid']] = array();
		$sos[$row['uid']]['nome'] = $row['docente'];
		$sos[$row['uid']]['classi'] = array();
	}
	$sos[$row['uid']]['classi'][$row['classe']] = $row['d_classe'];
}	

include "docenti_sostegno.html.php";
