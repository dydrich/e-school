<?php 

ini_set("display_errors", "1");

if(file_exists("./install/to_be_installed")){
	session_start();
	$_SESSION['step'] = 1;
	header("Location: install/index.php");
}

require_once "lib/SchoolYear.php";
require_once "lib/start.php";
$_SESSION['__path_to_root__'] = "/";

if(!isset($_SESSION['__config__'])){
	include_once "lib/load_env.php";
}

include "index.html.php";
