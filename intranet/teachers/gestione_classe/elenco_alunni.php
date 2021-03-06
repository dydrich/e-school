<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

require_once "../reload_class_in_session.php";

//$sel_alunni = "SELECT alunni.*, indirizzo, telefono1, telefono2, telefono3, email, messenger, blog FROM alunni LEFT JOIN indirizzi_alunni ON alunni.id_alunno = indirizzi_alunni.id_alunno LEFT JOIN profili_alunni ON indirizzi_alunni.id_alunno = profili_alunni.id_alunno WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY cognome, nome";
$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND attivo = '1' ORDER BY cognome, nome";
$res_alunni = $db->execute($sel_alunni);

$navigation_label = "gestione classe";
$drawer_label = "Elenco alunni (".$res_alunni->num_rows.")";

include "elenco_alunni.html.php";
