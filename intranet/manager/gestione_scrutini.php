<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "Registro elettronico: area ".$_SESSION['__role__'];

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$sel_scrutini = "SELECT * FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY data_pubblicazione DESC";
$res_scrutini = $db->execute($sel_scrutini);
$scrutini = array();
while ($row = $res_scrutini->fetch_assoc()){
	$scrutini[$row['quadrimestre']] = $row;
}

$sel_stati = "SELECT * FROM rb_stati_scrutinio ORDER BY id_stato DESC";
$res_stati = $db->execute($sel_stati);
$stati = array();
while ($row = $res_stati->fetch_assoc()){
	$stati[$row['id_stato']] = $row;
}

$field = "stato_scrutinio";
$data = "data_pubblicazione";
if ($school == 'Scuola primaria'){
	$field = "stato_scrutinio_sp";
	$data = "data_pubblicazione_sp";
}

$st_1q = $st_2q = "";
if (date("Y-m-d") > $scrutini[1][$data]){
	$st_1q = "closed";
}
if (date("Y-m-d") > $scrutini[2][$data]){
	$st_2q = "closed";
}

include "gestione_scrutini.html.php";

?>