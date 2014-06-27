<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 20/12/13
 * Time: 20.08
 */

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$doc = $_REQUEST['doc'];
$sel_docente = "SELECT rb_utenti.uid, rb_utenti.nome, rb_utenti.cognome, rb_docenti.*, rb_materie.materia, rb_materie.id_materia FROM rb_docenti, rb_utenti, rb_materie WHERE rb_utenti.uid = rb_docenti.id_docente AND rb_docenti.materia = rb_materie.id_materia AND uid = {$doc}";
$res_docente = $db->execute($sel_docente);
$docente = $res_docente->fetch_assoc();

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

$std = null;
if (isset($_REQUEST['std'])){
	$std = $_REQUEST['std'];
}

$sel_alunni = "SELECT id_alunno, cognome, nome, sezione, anno_corso FROM rb_alunni, rb_assegnazione_sostegno, rb_classi WHERE alunno = id_alunno AND rb_classi.id_classe = classe AND anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$doc} ORDER BY sezione, anno_corso, cognome, nome";
$res_alunni = $db->execute($sel_alunni);
$alunni = array();
$k = 0;
while ($row = $res_alunni->fetch_assoc()){
	if ($k == 0 && $std == null){
		$std = $row['id_alunno'];
	}
	$k++;
	$alunni[$row['id_alunno']]['data'] = $row;
	$alunni[$row['id_alunno']]['attivita'] = array();
	$sel_activities = "SELECT * FROM rb_attivita_sostegno WHERE alunno = {$row['id_alunno']} AND anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY data DESC";
	$res_activities = $db->execute($sel_activities);
	while ($r = $res_activities->fetch_assoc()){
		$alunni[$row['id_alunno']]['attivita'][$r['id']] = $r;
	}
}

include 'registro_sostegno.html.php';