<?php

include "../../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM);

header("Content-type: text/plain");

switch($_REQUEST['action']){
	case "1":
		$query = "UPDATE fc_classi_provenienza SET descrizione = '".$_REQUEST['class_name']."' WHERE id_classe = ".$_REQUEST['class_id'];
		break;
	case "2":
		$query = "INSERT INTO fc_classi_provenienza (descrizione, id_scuola) VALUES ('".$_REQUEST['class_name']."', ".$_REQUEST['school_id'].")";
		break;
	case "3":
		$upd = $db->executeQuery("UPDATE fc_alunni SET classe_provenienza = NULL WHERE classe_provenienza = ".$_REQUEST['class_id']);
		$query = "DELETE FROM fc_classi_provenienza WHERE id_classe = ".$_REQUEST['class_id'];
		break;
}
$out = "ok";
try{
	$db->executeUpdate($query);
	if($_REQUEST['action'] == 2){
		$sel_last = "SELECT MAX(id_classe) FROM fc_classi_provenienza";
		$max = $db->executeCount($sel_last);
		$out .= "#$max";
	}
} catch (MySQLException $ex){
	print "ko#".$ex->getQuery();
	exit;
}


print $out;
exit;

?>