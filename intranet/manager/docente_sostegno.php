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

$did = $_REQUEST['did'];

$sel_sos = "SELECT id, alunno, CONCAT_WS(' ', rb_utenti.cognome, rb_utenti.nome) AS docente, uid, classe, CONCAT(anno_corso, sezione) AS d_classe, ore FROM rb_utenti, rb_docenti, rb_classi, rb_assegnazione_sostegno WHERE uid = {$did} AND materia = {$sostegno} AND uid = id_docente AND id_docente = docente AND classe = id_classe AND anno = {$anno} ORDER BY cognome, nome, sezione, anno_corso";
$res_sos = $db->execute($sel_sos);
$sos = array();
$ore = 0;
$classi = array();
while ($row = $res_sos->fetch_assoc()){
	$user = $row['docente'];
	if (!$sos[$row['uid']]){
		$sos[$row['uid']] = array();
		$sos[$row['uid']]['nome'] = $row['docente'];
		$sos[$row['uid']]['classi'] = array();
	}
	$sos[$row['uid']]['classi'][$row['classe']] = $row['d_classe'];
	$ore += $row['ore'];
	if (!in_array($row['classe'], $classi)){
		$classi[$row['classe']] = array();
		$classi[$row['classe']]['nome'] = $row['d_classe'];
	}
}
foreach ($classi as $k => $c) {
	$classi[$k]['alunni'] = array();
	$sel_al = "SELECT id_alunno, cognome, nome FROM rb_alunni WHERE id_classe = {$k} AND attivo = '1' ORDER BY cognome, nome";
	$res_al = $db->execute($sel_al);
	while ($r = $res_al->fetch_assoc()){
		$classi[$k]['alunni'][$r['id_alunno']] = $r['cognome']." ".$r['nome'];
	}
}

include 'docente_sostegno.html.php';