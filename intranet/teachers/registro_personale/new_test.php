<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Test.php";

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
	$test = new \eschool\Test($_REQUEST['test'], new MySQLDataLoader($db), null, true);
	//list($date, $time) = explode(" ", $test->getTestDate());
	list($y, $m, $d) = explode("-", $test->getTestDate());
	//list($h, $mi) = explode(":", $time);
	$m--;
}
else {
	$_REQUEST['test'] = 0;
}

$selected = array();
if (isset($_SESSION['__user_config__']['tipologia_prove'])) {
	$selected = $_SESSION['__user_config__']['tipologia_prove'];
}
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
