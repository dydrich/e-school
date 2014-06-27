<?php

require_once '../../lib/start.php';

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$selected = $_SESSION['__user_config__']['tipologia_prove'];

$sel_prove = "SELECT * FROM rb_tipologia_prove";
try {
	$res_prove = $db->executeQuery($sel_prove);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}

$navigation_label = "Registro elettronico - Configurazioni utente";

include "grade_type.html.php";