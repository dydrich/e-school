<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/04/15
 * Time: 17.53
 *
 * default software theme manager
 */
require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$admin_level = 0;

$_SESSION['__path_to_root__'] = "../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "registro elettronico ";
$drawer_label = "Configurazione tema";

$sel_env = "SELECT * FROM rb_config WHERE variabile = 'selected_theme'";
$res_env = $db->executeQuery($sel_env);
while ($r = $res_env->fetch_assoc()) {
	$th = $r['valore'];
}

$sel_themes = "SELECT * FROM rb_themes ORDER BY id_tema";
$res_themes = $db->executeQuery($sel_themes);
$themes = array();
$default_theme = array();

while ($row = $res_themes->fetch_assoc()) {
	if ($row['id_tema'] == $th) {
		$default_theme = array("id" => $row['id_tema'], "name" => $row['nome'], "dir" => $row['directory'], "img" => $row['image']);
	}
	else {
		$themes[$row['id_tema']] = array("id" => $row['id_tema'], "name" => $row['nome'], "dir" => $row['directory'], "img" => $row['image']);
	}
}

include "scegli_tema.html.php";
