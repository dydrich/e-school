<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area amministrazione e segreteria";

if(!isset($_GET['offset']))
    $offset = 0;
else
    $offset = $_GET['offset'];

$limit = 15;
$l_ext = $offset + 1;
$r_ext = $limit + $offset;

$order = "name";
$param_order = "rb_alunni.cognome, rb_alunni.nome";
if(isset($_REQUEST['order'])){
	$order = $_REQUEST['order'];
	if($_REQUEST['order'] == "class"){
		$param_order = "rb_classi.sezione, rb_classi.anno_corso, rb_alunni.cognome";
	}
}

$school_order = "";
if ($_SESSION['__school_order__']){
	$school_order = "AND rb_classi.ordine_di_scuola = {$_SESSION['__school_order__']}";
}

$sel_alunni = "SELECT rb_alunni.id_alunno, rb_alunni.nome, rb_alunni.cognome, rb_classi.sezione, rb_classi.anno_corso, ripetente FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND attivo = '1' {$school_order} ORDER BY $param_order";

if(!isset($_GET['second'])){
	$res_alunni = $db->execute($sel_alunni);
	//print $sel_links;
	$count = $res_alunni->num_rows;
	$_SESSION['count_teac'] = $count;
}
else{
	$sel_alunni .= " LIMIT $limit OFFSET $offset";
	$res_alunni = $db->execute($sel_alunni);
}

if ($offset == 0) {
	$page = 1;
}
else {
	$page = ($offset / $limit) + 1;}

$pagine = ceil($_SESSION['count_teac'] / $limit);
if ($pagine < 1) {
	$pagine = 1;
}

if ($r_ext > $_SESSION['count_teac']) {
	$r_ext = $_SESSION['count_teac'];
}

// dati per la paginazione (navigate.php)
$colspan = 3;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_teac";
$row_class = "docs_row";
$row_class_menu = " docs_row_menu";
$nav_params = "&order=$order";

include "elenco_alunni.html.php";
