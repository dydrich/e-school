<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$sel_docenti = "SELECT cognome, nome, uid FROM rb_docenti, rb_utenti WHERE uid = id_docente ORDER BY cognome, nome";
//print $sel_docenti;
$res_docenti = $db->executeQuery($sel_docenti);

include "elenco_docenti.html.php";

?>