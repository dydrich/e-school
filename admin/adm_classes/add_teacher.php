<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$cls = $_GET['cls'];

$sel_teac = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti WHERE id_docente = uid AND (materia = 27 OR materia = 41)  ORDER BY cognome, nome";
$res_teac = $db->execute($sel_teac);

include "add_teacher.html.php";