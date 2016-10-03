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

$colors = array("9c27b0", "3f51b5", "009688", "ff9800", "64dd17", "fdd835");

if (isset($_SESSION['__user__'])) {
	$groups = $_SESSION['__user__']->getGroups();
	$col_length = 50 * (count($groups) + 2);
}

//include "index.html.php";
include "manutention.php";
