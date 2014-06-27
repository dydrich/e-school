<?php

require_once "../../lib/start.php";
require_once "../../lib/Goal.php";

check_session();
check_permission(DOC_PERM);

header("Content-type: text/plain");

$goal_manager = new Goal($_POST, new MySQLDataLoader($db));
if ($_POST['action'] == 1){
	try {
		$goal_manager->insert();
	} catch (MySQLException $ex){
		echo "kosql;".$ex->getQuery().";".$ex->getMessage();
		exit;
	}
}
else if ($_POST['action'] == 2){
	try {
		$goal_manager->delete();
	} catch (MySQLException $ex){
		echo "kosql;".$ex->getQuery().";".$ex->getMessage();
		exit;
	}
}
else if ($_POST['action'] == 3){
	try {
		$goal_manager->update();
	} catch (MySQLException $ex){
		echo "kosql;".$ex->getQuery().";".$ex->getMessage();
		exit;
	}
}

?>