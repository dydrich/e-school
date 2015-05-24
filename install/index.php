<?php

include_once '../lib/functions.lib.php';
include_once '../lib/classes.php';

session_start();

$_SESSION['__path_to_root__'] = "../";

if(isset($_SESSION['__config__'])){
	unset($_SESSION['__config__']);
}

if(!isset($_SESSION['step'])){
	$_SESSION['step'] == 1;
}

$drawer_label = "Installazione del software";

include_once 'index.html.php';
