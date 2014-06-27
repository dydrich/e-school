<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$stid = $_REQUEST['stid'];
$sel_types = "SELECT * FROM rb_tipi_note_didattiche ORDER BY id_tiponota ASC";
try{
	$res_types = $db->executeQuery($sel_types);
} catch (MySQLException $ex){
	$ex->fake_alert();
}
$referer = $_SERVER['HTTP_REFERER'];

if(isset($_REQUEST['id_nota'])){
	// update or delete
	$sel_note = "SELECT * FROM rb_note_didattiche WHERE id_nota = ".$_REQUEST['id_nota'];
	try{
		$res_note = $db->executeQuery($sel_note);
	} catch (MySQLException $ex){
		$ex->fake_alert();
	}
	$nota = $res_note->fetch_assoc();
}

include "new_note.html.php";

?>