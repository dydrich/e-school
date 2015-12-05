<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$s_order = "";
if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$s_order = "AND rb_tipologia_scuola.id_tipo = ".$_SESSION['__school_order__'];
}

$sel_user = "SELECT id_docente, rb_utenti.nome, rb_utenti.cognome, rb_materie.materia, ruolo, rb_tipologia_scuola.tipo AS tipologia FROM rb_tipologia_scuola, rb_utenti, rb_docenti LEFT JOIN rb_materie ON rb_docenti.materia = rb_materie.id_materia WHERE id_docente = rb_utenti.uid AND rb_docenti.tipologia_scuola = id_tipo ".$s_order." ORDER BY cognome, nome ";

try {
    $res_user = $db->execute($sel_user);
    //print $sel_links;
    $count = $res_user->num_rows;
    $_SESSION['count_docenti'] = $count;
} catch (MySQLException $ex) {
    $ex->redirect();
}

// estraggo le materie
$sel_m = "SELECT id_materia, materia, tipologia_scuola FROM rb_materie WHERE idpadre IS NULL AND (id_materia > 2 AND id_materia <> 40) ORDER BY materia";
$res_m = $db->execute($sel_m);

// tipologie scuola
$sel_tipologie = "SELECT * FROM rb_tipologia_scuola WHERE has_admin = 1 AND attivo = 1";
$res_tipologie = $db->executeQuery($sel_tipologie);

/*
 * procedura guidata prima installazione
* first install wizard
*/
$goback = "Torna al menu";
$goback_link = "../index.php";
if(basename($_SERVER['HTTP_REFERER']) == "wiz_first_install.php?step=2"){
	$goback = "Torna al wizard";
	$goback_link = "../wiz_first_install.php?step=2";
}

$navigation_label = "gestione utenti";
$drawer_label = "Elenco docenti (estratti ".$count." record) ";

include "teachers.html.php";
