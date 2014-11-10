<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", "1");

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area_label__'] = "Area amministrazione";

$sel_utenti = "SELECT rb_utenti.uid AS id, cognome, nome FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND gid <> 4 ORDER BY cognome, nome";
$res_utenti = $db->execute($sel_utenti);

$sel_gr = "SELECT gid, nome FROM rb_gruppi ";
$groups = $db->executeQuery($sel_gr);

$navigation_label = "sviluppo";
$drawer_label = "Verifica permessi utente";
$admin_level = 0;

include "check_perms.html.php";
