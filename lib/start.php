<?php

require_once "users_classes.php";
require_once "functions.lib.php";
require_once "database.lib.php";
require_once "classes.php";
require_once "SchoolYear.php";

session_start();

require_once 'conn.php';

/*inserisco la visita*/
$page = $_SERVER['SCRIPT_FILENAME'];
$ip = $_SERVER['REMOTE_ADDR'];
$uid = 0;
$perms = 0;
$uri = $_SERVER['SCRIPT_NAME'];
if(isset($_SESSION['__user__'])){
	$uid = $_SESSION['__user__']->getUid();
	$perms = $_SESSION['__user__']->getPerms();
}
$ins = "INSERT INTO rb_visite (data_ora, ip_address, page, id_utente, uri, permessi) VALUES (NOW(), '{$ip}', '{$page}', {$uid}, '{$uri}', $perms)";
$r_ins = $db->executeUpdate($ins);

/*
$path = $_SERVER['REQUEST_URI'];
$pos = strpos($path, "admin");
if($pos !== false && (basename($path) != "login.php")){
	check_session();
	include_once $_SESSION['__config__']['html_root']."/admin/admin_timeout.php";
}
*/
//fix_magic_quotes();

/* debug */
if (isset($_SESSION['__config__'])){
	define("DISPLAY_ERRORS", $_SESSION['__config__']['debug']);
}

ini_set("default_charset", "utf-8");

/*
 * default theme
 */
$id_theme = $db->executeCount("SELECT valore FROM rb_config WHERE variabile = 'selected_theme'");
$_SESSION['default_theme'] = $db->executeCount("SELECT directory FROM rb_themes WHERE id_tema = {$id_theme}");

date_default_timezone_set("Europe/Rome");
