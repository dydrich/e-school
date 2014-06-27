<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

//$sel_alunni = "SELECT alunni.*, indirizzo, telefono1, telefono2, telefono3, email, messenger, blog FROM alunni LEFT JOIN indirizzi_alunni ON alunni.id_alunno = indirizzi_alunni.id_alunno LEFT JOIN profili_alunni ON indirizzi_alunni.id_alunno = profili_alunni.id_alunno WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY cognome, nome";
$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND attivo = '1' ORDER BY cognome, nome";
$res_alunni = $db->execute($sel_alunni);

$navigation_label = "Registro elettronico - ".$_SESSION['__classe__']->to_string();

include "elenco_alunni.html.php";

?>