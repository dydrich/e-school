<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

$perms = ($_SESSION['__user__']->getPerms()) ? $_SESSION['__user__']->getPerms() : $_SESSION['__perms__'];
//$nome = ($_SESSION['__user__']) ? $_SESSION['__user__']->getFullName() : $_SESSION['__fname__']." ".$_SESSION['__lname__'];

if(DIR_PERM&$perms)
$_SESSION['__role__'] = "Dirigente scolastico";
else
$_SESSION['__role__'] = "DSGA";

if($_REQUEST['stid'] != 0){
	$sel_student = "SELECT * FROM fc_alunni WHERE id_alunno = ".$_REQUEST['stid'];
	try{
		$res_student = $db->executeQuery($sel_student);
	} catch (MySQLException $ex){
		$ex->fake_alert();
	}
	$student = $res_student->fetch_assoc();
}

if(!isset($_REQUEST['rip']))
	$sel_classes_from = "SELECT fc_classi_provenienza.id_classe, fc_classi_provenienza.id_scuola, CONCAT_WS(', ', fc_scuole_provenienza.descrizione, fc_classi_provenienza.descrizione) AS description FROM fc_classi_provenienza, fc_scuole_provenienza WHERE fc_classi_provenienza.id_scuola <> 5 AND fc_classi_provenienza.id_scuola = fc_scuole_provenienza.id_scuola";
else
	$sel_classes_from = "SELECT id_classe, descrizione AS description FROM fc_classi_provenienza WHERE fc_classi_provenienza.id_scuola = 5 ORDER BY descrizione ";
$res_classes_from = $db->executeQuery($sel_classes_from);

include "student.html.php";

?>