<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Test.php";
require_once "../../../lib/RBUtilities.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

setlocale(LC_TIME, "it_IT.utf8");

$sel_test = "SELECT rb_verifiche.*, rb_tipologia_prove.tipologia AS tipo FROM rb_verifiche, rb_tipologia_prove WHERE id_verifica = {$_REQUEST['idt']} AND rb_verifiche.tipologia = rb_tipologia_prove.id";
$res_test = $db->execute($sel_test);
$_test = $res_test->fetch_assoc();

if ($_test['id_docente'] != $_SESSION['__user__']->getUid()) {
	$_SESSION['__referer__'] = "registro_personale/tests.php";
	header("Location: {$_SESSION['__path_to_reg_home__']}no_permission.php");
}

$test = new \eschool\Test($_test['id_verifica'], new MySQLDataLoader($db), $_test, false);

$voti_religione = \RBUtilities::getReligionGrades();

$selected = array();
if (isset($_SESSION['__user_config__']['tipologia_prove'])) {
	$selected = $_SESSION['__user_config__']['tipologia_prove'];
}

$prove = array();
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
while ($row = $res_prove->fetch_assoc()) {
	$prove[$row['id']] = $row['tipologia'];
}

$sel_obj = "SELECT nome FROM rb_obiettivi, rb_obiettivi_verifica WHERE id_obiettivo = rb_obiettivi.id AND id_verifica = {$_REQUEST['idt']}";
$res_obj = $db->executeQuery($sel_obj);
$obj = array();
while ($row = $res_obj->fetch_assoc()){
	$obj[] = $row['nome'];
}
$string_obj = "";
$string_obj = implode(" || ", $obj);

$navigation_label = "registro personale ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Dettaglio verifica di ". $test->getSubject()->getDescription()." del <span id='date_label'>".format_date(substr($test->getTestDate(), 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/")."</span>";

include "test.html.php";
