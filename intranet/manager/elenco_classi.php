<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$anno = $_SESSION['__current_year__']->get_ID();

$order = "sez";
$param_order = "sezione, anno_corso";
if(isset($_REQUEST['order'])){
	$order = $_REQUEST['order'];
	if($_REQUEST['order'] == "year"){
		$param_order = "anno_corso, sezione";
	}
}

$classes_table = "rb_classi";
if ($_SESSION['__school_order__']){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
	$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];
}

$sel_cls = "SELECT {$classes_table}.id_classe, anno_corso, tempo_prolungato, sezione, rb_sedi.nome, tipo, COUNT(id_alunno) AS num_alunni FROM {$classes_table} LEFT JOIN rb_alunni ON {$classes_table}.id_classe = rb_alunni.id_classe LEFT JOIN rb_sedi ON sede = rb_sedi.id_sede LEFT JOIN rb_tipologia_scuola ON id_tipo = {$classes_table}.ordine_di_scuola AND rb_alunni.attivo = '1' GROUP BY {$classes_table}.id_classe, anno_corso, tempo_prolungato, sezione, rb_sedi.nome, tipo ORDER BY $param_order ";

$res_cls = $db->execute($sel_cls);
$count = $res_cls->num_rows;
$_SESSION['count_cls'] = $count;

// dati per la paginazione (navigate.php)
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_cls";
$row_class = "list_row";
$row_class_menu = " list_row_menu";

$drawer_label = "Elenco classi ".$school;

include "elenco_classi.html.php";
