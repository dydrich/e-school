<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$stid = isset($_REQUEST['stid']) ? $_REQUEST['stid'] : null;
$nt = "";
if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && (!$_SESSION['__user__']->isAdministrator()) ){
	$nt = "WHERE id_tiponota < 10 ";
}
$sel_types = "SELECT * FROM rb_tipi_note_disciplinari $nt ORDER BY id_tiponota ASC";
try{
	$res_types = $db->executeQuery($sel_types);
} catch (MySQLException $ex){
	$ex->fake_alert();
}
$referer = $_SERVER['HTTP_REFERER'];

if(isset($_REQUEST['id_nota'])){
	$sel_nota = "SELECT * FROM rb_note_disciplinari WHERE id_nota = ".$_REQUEST['id_nota'];
	try{
		$res_nota = $db->executeQuery($sel_nota);
	} catch (MySQLException $ex){
		$ex->fake_alert();
	}
	$nota = $res_nota->fetch_assoc();
}

include "new_note.html.php";
