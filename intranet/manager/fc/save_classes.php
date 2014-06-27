<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

$db->executeUpdate("CREATE TABLE classes_old AS SELECT * FROM classi");

try{
	$db->executeUpdate("BEGIN");
	$db->executeUpdate("TRUNCATE TABLE classi");
	$db->executeUpdate("INSERT INTO classi SELECT * FROM nuove_classi");
	$db->executeUpdate("COMMIT");
} catch (MySQLException $ex){
	print ("ko|".$ex->getMessage()."|".$ex->getQuery());
	$db->executeUpdate("ROLLBACK");
	exit;
}

print "ok";
exit;

?>