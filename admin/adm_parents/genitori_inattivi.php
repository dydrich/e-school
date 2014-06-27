<?php

require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";
require_once "../../lib/Widget.php";
require_once "../../lib/PageMenu.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$sel_new_parents = "SELECT rb_utenti.uid, rb_utenti.nome, rb_utenti.cognome, rb_utenti.username FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND gid = 4 AND rb_utenti.uid NOT IN (SELECT id_genitore FROM rb_genitori_figli) 
					UNION SELECT rb_utenti.uid, rb_utenti.nome, rb_utenti.cognome, rb_utenti.username FROM rb_utenti, rb_genitori_figli, rb_alunni WHERE uid = id_genitore AND rb_genitori_figli.id_alunno = rb_alunni.id_alunno AND attivo = '0' ORDER BY cognome, nome";
$res_new_parents = $db->execute($sel_new_parents);

$navigation_label = "Area amministrazione: gestione genitori";

include "genitori_inattivi.html.php";