<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$sel_alunno = "SELECT rb_alunni.*, rb_indirizzi_alunni.* FROM rb_alunni LEFT JOIN rb_indirizzi_alunni ON rb_alunni.id_alunno = rb_indirizzi_alunni.id_alunno WHERE rb_alunni.id_alunno = {$_REQUEST['stid']}";
$res_alunno = $db->execute($sel_alunno);
$alunno = array();
if ($res_alunno->num_rows > 0){
	$alunno = $res_alunno->fetch_assoc();
}

$navigation_label = "gestione classe";
$drawer_label = "Dettaglio alunno ".$alunno['cognome']." ".$alunno['nome'];

include "dettaglio_alunno.html.php";
