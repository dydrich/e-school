<?php

require_once "../../lib/start.php";
require_once "../../lib/RBTime.php";

check_session();
check_permission(DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$classes_table = "rb_classi";
if ($_SESSION['__school_order__']){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];
}

$sel_classes = "SELECT * FROM {$classes_table} ORDER BY sezione, anno_corso";
$res_classes = $db->execute($sel_classes);

$drawer_label = "Verifica registri di classe";

include "verifica_registro.html.php";
