<?php 

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if(isset($_REQUEST['id_voto'])){
	$sel_voto = "SELECT rb_voti.*, rb_tipologia_prove.label FROM rb_voti, rb_tipologia_prove WHERE rb_voti.tipologia = rb_tipologia_prove.id AND id_voto = ".$_REQUEST['id_voto'];
	$res_voto = $db->executeQuery($sel_voto);
	$voto = $res_voto->fetch_assoc();
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

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

if(isset($_REQUEST['referer']) && $_REQUEST['referer'] == "list"){
	$referer = "index.php";
}
else{
	$referer = "";
}

include "mark.html.php";

?>
