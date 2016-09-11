<?php

require_once "../../lib/start.php";

check_session(FAKE_WINDOW);
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = 0;

$offset = 0;
if (isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}

$school_orders = [];
$res_orders = $db->executeQuery("SELECT * FROM rb_tipologia_scuola WHERE attivo = 1");
if ($res_orders->num_rows > 0) {
    while ($row = $res_orders->fetch_assoc()) {
        $school_orders[$row['id_tipo']] = $row;
    }
}

$teachers = [];
try {
    $res_teachers = $db->executeQuery("SELECT id_docente, rb_utenti.nome, rb_utenti.cognome, rb_docenti.tipologia_scuola, rb_tipologia_scuola.codice AS tipologia 
                                      FROM rb_tipologia_scuola, rb_utenti, rb_docenti 
                                      WHERE id_docente = rb_utenti.uid AND rb_docenti.tipologia_scuola = id_tipo 
                                      ORDER BY cognome, nome ");
} catch (MySQLException $ex) {
    $ex->redirect();
}
while ($row = $res_teachers->fetch_assoc()) {
    $teachers[$row['id_docente']] = $row;
}

if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
	$sel_sede = "SELECT * FROM rb_sedi WHERE id_sede = ".$_REQUEST['id'];
	$res_sede = $db->executeQuery($sel_sede);
	$sede = $res_sede->fetch_assoc();
	$_i = $_REQUEST['id'];
}
else{
	$my_date = date("d/m/Y");
	$_i = 0;
}

$navigation_label = "gestione scuola";
$drawer_label = "Dettaglio sede";

include "dettaglio_sede.html.php";
