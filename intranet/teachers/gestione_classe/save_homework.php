<?php

/**
    gestione compiti
*/

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: text/plain");

$doc = $_SESSION['__user__']->getUid();
$classe = $_SESSION['__classe__']->get_ID();
$anno = $_SESSION['__current_year__']->get_ID();
$data_inizio = format_date($_REQUEST['data_inizio'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$materia = $_REQUEST['materia'];
$descrizione = $db->real_escape_string($_REQUEST['descrizione']);
$note = $db->real_escape_string($_REQUEST['note']);

if($_POST['id_act'] == 0){
	$query = "INSERT INTO rb_impegni (data_assegnazione, data_inizio, docente, classe, anno, materia, descrizione, note, tipo) VALUES (NOW(), '$data_inizio', $doc, $classe, $anno, $materia, '$descrizione', '$note', 2)";
	$msg = "Compito assegnato correttamente";
}
else{
	if($_POST['del'] == 1){
		$query = "DELETE FROM rb_impegni WHERE id_impegno = ".$_POST['id_act'];
		$msg = "Il compito e' stato cancellato";
	}
	else{
		$query = "UPDATE rb_impegni SET data_inizio = '$data_inizio', docente = $doc, classe = $classe, anno = $anno, materia = $materia, descrizione = '$descrizione', note = '$note' WHERE id_impegno = ".$_POST['id_act'];
		$msg = "Compito modificato con successo";
	}
}

try{
	$rs = $db->executeQuery($query);
} catch (MySQLException $ex){
	print "ko#".$ex->getMessage()."#".$ex->getQuery();
	exit;
}

$res = "ok#$msg#$query";

print $res;
exit;

?>