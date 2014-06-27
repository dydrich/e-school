<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$label = "Nuova verifica";

if(isset($_REQUEST['test'])){
	// update
	$label = "Modifica verifica";
	$sel_test = "SELECT * FROM rb_verifiche WHERE id_verifica = ".$_REQUEST['test'];
	$res_test = $db->executeQuery($sel_test);
	$test = $res_test->fetch_assoc();
	list($date, $time) = explode(" ", $test['data_verifica']);
	list($y, $m, $d) = explode("-", $date);
	list($h, $mi) = explode(":", $time);
	$m--;
}

$selected = $_SESSION['__user_config__']['tipologia_prove'];
if (count($selected) > 0){
	$sel_prove = "SELECT * FROM rb_tipologia_prove WHERE id IN (".join(",", $selected).")";
}
else{
	$sel_prove = "SELECT * FROM rb_tipologia_prove WHERE `default` = 1";
}
try {
	$res_prove = $db->executeQuery($sel_prove);
} catch (MySQLException $ex){
	$ex->redirect();
	exit;
}

include "new_test.html.php";

?>