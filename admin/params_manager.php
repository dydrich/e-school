<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

if($_POST['action'] == 1 || $_POST['action'] == 3){
	$name = $db->real_escape_string(utf8_encode($_POST['titolo']));
	$q = $_POST['q'];
	if ($q = 0) $q = "";
}

switch($_REQUEST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_parametri_pagella (nome, quadrimestre, ordine_scuola) VALUES ('{$name}', ".field_null($q, false).", {$_REQUEST['school_order']})";
		$msg = "Parametro inserito correttamente";
		break;
	case 2:     // cancellazione
		$db->executeUpdate("DELETE FROM rb_giudizi_parametri_pagella WHERE id_parametro = {$_REQUEST['_i']}");
		$statement = "DELETE FROM rb_parametri_pagella WHERE id = ".$_REQUEST['_i'];
		$msg = "Cancellazione eseguita correttamente";
		break;
	case 3:     // modifica
		$statement = "UPDATE rb_parametri_pagella set nome = '{$name}', quadrimestre = ".field_null($q, false)." WHERE id = ".$_REQUEST['_i'];
		$msg = "Parametro aggiornato correttamente";
		break;
	case 4:
		/*
		 * inserimento nuovo valore
		 */
		$name = $db->real_escape_string($_POST['giudizio']);
		$statement = "INSERT INTO rb_giudizi_parametri_pagella (id_parametro, giudizio) VALUES ({$_REQUEST['_i']}, '{$name}')";
		break;
	case 5:
		/*
		 * cancellazione valore
		 */
		$statement = "DELETE FROM rb_giudizi_parametri_pagella WHERE id = {$_REQUEST['_i']}";
		break;
	case 6:
		/*
		 * modifica valore
		 */
		$name = $db->real_escape_string($_REQUEST['val']);
		$statement = "UPDATE rb_giudizi_parametri_pagella SET giudizio = '{$name}' WHERE id = {$_REQUEST['_i']}";
		break;
}
header("Content-type: text/plain");
try{
	$recordset = $db->executeUpdate($statement);
} catch (MySQLException $ex){
	print "ko|".$ex->getMessage()."|".$ex->getQuery();
	exit;
}

if ($_REQUEST['action'] != 6){
	print "ok|".$msg."|".$recordset;
}
else{
	echo $name;
}
exit;