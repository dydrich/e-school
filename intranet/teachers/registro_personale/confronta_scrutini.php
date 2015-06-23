<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 28/05/14
 * Time: 18.32
 */

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";
require_once "../../../lib/RBUtilities.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

$back_link = "scrutini.php?q=2";
if(isset($_REQUEST['subject'])) {
	$_SESSION['__materia__'] = $_REQUEST['subject'];
	$back_link .= "&subject=".$_REQUEST['subject'];
}

$sel_dati = "SELECT rb_alunni.cognome, rb_alunni.nome, alunno, voto, quadrimestre FROM rb_alunni LEFT JOIN rb_scrutini ON id_alunno = alunno WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND id_classe = ". $_SESSION['__classe__']->get_ID() ." AND classe = id_classe AND materia = ".$_SESSION['__materia__']." ORDER BY cognome, nome";
$res_dati = $db->execute($sel_dati);
$dati = array();
while ($row = $res_dati->fetch_assoc()){
	if (!isset($dati[$row['alunno']])){
		$dati[$row['alunno']] = array('lname' => $row['cognome'], 'fname' => $row['nome'], 'voto1q' => 0, 'voto2q' =>0);
	}
	if ($row['quadrimestre'] == 1){
		$dati[$row['alunno']]['voto1q'] = $row['voto'];
	}
	else {
		$dati[$row['alunno']]['voto2q'] = $row['voto'];
	}
}

$mat = $_SESSION['__user__']->getSubject();
/*
 * controllo materia alternativa
 */
if ($ordine_scuola == 1) {
	$alt_subject = 46;
	$id_religione = 26;
}
else {
	$alt_subject = 47;
	$id_religione = 30;
}
$sel_alt_sub = "SELECT COUNT(*) FROM rb_materia_alternativa WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND classe = ".$_SESSION['__classe__']->get_ID()." AND docente = ".$_SESSION['__user__']->getUid();
$res_alt_sub = $db->executeCount($sel_alt_sub);
$subject_number = count($_SESSION['__subjects__']);
if ($res_alt_sub > 0) {
	$subject_number++;
}

if($subject_number > 0) {
	$k = 0;
	if (isset($_REQUEST['subject']) && $_REQUEST['subject'] == $alt_subject) {
		$idm = $alt_subject;
		$_mat = "Mat. alt.";
	}
	else {
		foreach ($_SESSION['__subjects__'] as $mt) {
			//print "while";
			if (isset($_REQUEST['subject'])) {
				if ($_REQUEST['subject'] == $mt['id']) {
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
			}
			else {
				if (isset($_SESSION['__materia__'])) {
					if ($_SESSION['__materia__'] == $mt['id']) {
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
					else {
						if ($k == 0) {
							$idm = $mt['id'];
							$_mat = $mt['mat'];
						}
					}
				}
				else {
					if ($k == 0) {
						//print "k==0";
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
				}
			}
			$k++;
		}
	}
	$_SESSION['__materia__'] = $idm;
}

if(isset($_REQUEST['subject'])) {
	$_SESSION['__materia__'] = $_REQUEST['subject'];
}

/*
 * controllo per la materia alternativa:
 * se richiesta va caricato il dato di religione
 */
$load_mat = $_SESSION['__materia__'];
if ($load_mat == $alt_subject) {
	$load_mat = $id_religione;
}

$sel_dati = "SELECT rb_alunni.cognome, rb_alunni.nome, alunno, voto, assenze FROM rb_alunni LEFT JOIN rb_scrutini ON id_alunno = alunno WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND id_classe = ". $_SESSION['__classe__']->get_ID() ." AND classe = id_classe AND materia = {$load_mat} ORDER BY cognome, nome";
$res_dati = $db->execute($sel_dati);

$esonerati = array();
if ($_SESSION['__user__']->getSubject() == 26 || $_SESSION['__materia__'] == 30 || $_SESSION['__materia__'] == 46 || $_SESSION['__materia__'] == 47) {
	/*
	 * esoneri religione
	 */
	$sel_esonerati = "SELECT alunno FROM rb_esoneri_religione WHERE classe = ".$_SESSION['__classe__']->get_ID();
	$res_esonerati = $db->executeQuery($sel_esonerati);
	if ($res_esonerati->num_rows > 0) {
		while ($row = $res_esonerati->fetch_assoc()) {
			$esonerati[] = $row['alunno'];
		}
	}
}

$avg1 = $db->executeCount("SELECT ROUND(AVG(voto), 2) FROM rb_scrutini WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND classe = ". $_SESSION['__classe__']->get_ID() ." AND materia = ".$_SESSION['__materia__']." AND quadrimestre = 1");
$avg2 = $db->executeCount("SELECT ROUND(AVG(voto), 2) FROM rb_scrutini WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND classe = ". $_SESSION['__classe__']->get_ID() ." AND materia = ".$_SESSION['__materia__']." AND quadrimestre = 2");

$navigation_label = "Registro personale del docente - Classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Confronta scrutini";

include "confronta_scrutini.html.php";
