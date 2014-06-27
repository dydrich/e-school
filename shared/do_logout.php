<?php

session_start();
if (!$_SESSION){
	include_once '../lib/start.php';
	$location = $db->executeCount("SELECT valore FROM rb_config WHERE variabile = 'root_site'");
}
else {
	$location = $_SESSION['__config__']['root_site'];
	session_destroy();
	unset($_SESSION);
}
header("Location: ".$location);
exit;

?>