<?php

include_once '../lib/functions.lib.php';
include_once '../lib/classes.php';

session_start();

if(isset($_SESSION['__config__'])){
	unset($_SESSION['__config__']);
}

if(!isset($_SESSION['step'])){
	$_SESSION['step'] == 1;
}

include_once 'index.html.php';

?>