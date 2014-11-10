<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/09/14
 * Time: 18.55
 */
require_once "../../lib/start.php";

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "area privata";
$drawer_label = "Theme manager";

$sel_themes = "SELECT * FROM rb_themes ORDER BY id_tema";
$res_themes = $db->executeQuery($sel_themes);
$themes = array();
$default_theme = array();
$th = getTheme();
while ($row = $res_themes->fetch_assoc()) {
	if ($row['directory'] == $th) {
		$default_theme = array("id" => $row['id_tema'], "name" => $row['nome'], "dir" => $row['directory'], "img" => $row['image']);
	}
	else {
		$themes[$row['id_tema']] = array("id" => $row['id_tema'], "name" => $row['nome'], "dir" => $row['directory'], "img" => $row['image']);
	}
}

include "themes.html.php";
