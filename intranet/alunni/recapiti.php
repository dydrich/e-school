<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico - Area studenti";

$sel_rec = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = ".$_SESSION['__user__']->getUid();
$res_rec = $db->execute($sel_rec);
if($res_rec->num_rows > 0)
	$rec = $res_rec->fetch_assoc();

include "recapiti.html.php";

?>