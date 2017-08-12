<?php

require_once 'AnnoScolastico.php';

// anno scolastico attuale in sessione
$sel_anno = "SELECT * FROM rb_anni WHERE data_inizio <= NOW() ORDER BY id_anno DESC LIMIT 1";
$res_anno = $db->executeQuery($sel_anno);
$current_year = new AnnoScolastico($res_anno->fetch_assoc());
$_SESSION['__current_year__'] = $current_year;

// estrazione dati di configurazione
$sel_config = "SELECT * FROM rb_config";
$res_config = $db->executeQuery($sel_config);
$config = array();
while($row = $res_config->fetch_assoc()){
	$config[$row['variabile']] = stripslashes($row['valore']);
}
$_SESSION['__config__'] = $config;

$sel_modules = "SELECT id, code_name, active FROM rb_modules";
$res_modules = $db->executeQuery($sel_modules);
$_SESSION['__modules__'] = array();
while($mod = $res_modules->fetch_assoc()){
	$_SESSION['__modules__'][$mod['code_name']]['id'] = $mod['id'];
	$_SESSION['__modules__'][$mod['code_name']]['installed'] = $mod['active'];
}

// ordini di scuola
$sel_ord = "SELECT * FROM rb_tipologia_scuola WHERE has_admin = 1 AND attivo = 1";
$res_ord = $db->executeQuery($sel_ord);
$_SESSION['__school_level__'] = array();
while($level = $res_ord->fetch_assoc()){
	$_SESSION['__school_level__'][$level['id_tipo']] = $level['tipo'];
	if($res_ord->num_rows < 2){
		$_SESSION['__only_school_level__'] = $level['id_tipo'];
		$_SESSION['__school_order__'] = $level['id_tipo'];
	}
}

// dati anno per ordini di scuola
$_SESSION['__school_year__'] = array();
$sel_y = "SELECT * FROM rb_dati_lezione WHERE id_anno = ".$_SESSION['__current_year__']->get_ID();
$res_y = $db->executeQuery($sel_y);
while($row = $res_y->fetch_assoc()){
	$sy = new SchoolYear($_SESSION['__current_year__']);
	$sy->setClassesEndDate($row['data_termine_lezioni']);
	$sy->setClassesStartDate($row['data_inizio_lezioni']);
	$sy->setFirstSessionEndDate($row['data_fine_1_sessione']);
	$sy->setSecondSessionEndDate($row['data_fine_2_sessione']);
	$days = explode(",", $row['vacanze']);
	$sy->setHolydays($days);
	$sy->setID($row['id']);
	$sy->setSessions($row['sessioni']);
	$_SESSION['__school_year__'][$row['id_ordine_scuola']] = $sy;
}
