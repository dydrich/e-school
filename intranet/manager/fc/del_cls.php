<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

header("Content-type: text/plain");

$id = $_REQUEST['id'];

/* need a transaction */
$db->executeUpdate("BEGIN");
try{
	$upd = "UPDATE fc_alunni SET id_classe = NULL WHERE id_classe = $id";
	$db->executeUpdate($upd);
	$del = "DELETE FROM fc_classi WHERE id_classe = $id";
	$db->executeUpdate($del);
} catch (MySQLException $ex){
	$db->executeUpdate("ROLLABCK");
	print ("ko;".$ex->getMessage());
	exit;
}
$db->executeUpdate("COMMIT");

print "ok;$id";
exit;

?>