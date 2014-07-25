<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$t = $_REQUEST['t'];
$upd = 0;
$title = "Inserisci nuova attivit&agrave; per la classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

if($t != 0){
	// modifica di una attivita` presente
	$upd = 1;
	$title = "Dettaglio attivit&agrave;";
	$sel_impegno = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni LEFT JOIN rb_materie ON rb_materie.id_materia = rb_impegni.materia WHERE rb_impegni.id_impegno = ".$t;
	$res_impegno = $db->execute($sel_impegno);
	$att = $res_impegno->fetch_assoc();
	list($da, $oa) = explode(" ", $att['data_assegnazione']);
	list($di, $oi) = explode(" ", $att['data_inizio']);
	list($df, $of) = explode(" ", $att['data_fine']);
}

$sel_materie = "SELECT rb_materie.id_materia, rb_materie.materia FROM rb_materie, rb_docenti WHERE (rb_docenti.materia = rb_materie.id_materia OR rb_docenti.materia = rb_materie.idpadre OR rb_materie.idpadre IN (SELECT rb_materie.id_materia FROM rb_materie, rb_docenti WHERE idpadre = rb_docenti.materia AND rb_docenti.id_docente = ".$_SESSION['__user__']->getUid().")) AND rb_docenti.id_docente = ".$_SESSION['__user__']->getUid();
$res_materie = $db->execute($sel_materie);

$navigation_label = "Registro elettronico - ".$_SESSION['__classe__']->to_string();

include "dettaglio_attivita.html.php";
