<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area_label__'] = "Area amministrazione";

$classes_table = "rb_classi";
if (isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
}
else if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
}

$sel_cls = "SELECT id_classe, anno_corso, sezione, $classes_table.ordine_di_scuola, rb_sedi.nome FROM {$classes_table}, rb_sedi, rb_tipologia_scuola WHERE sede = rb_sedi.id_sede AND {$classes_table}.ordine_di_scuola = id_tipo AND rb_tipologia_scuola.attivo = 1 ORDER BY sezione, anno_corso, sede ";
$res_cls = $db->execute($sel_cls);
$classi = array();
while ($row = $res_cls->fetch_assoc()){
	$classi[$row['id_classe']] = array();
	$classi[$row['id_classe']]['cls'] = $row;
	$classi[$row['id_classe']]['alunni'] = array();
}

$sel_alunni = "SELECT rb_alunni.*, anno_corso, sezione FROM rb_alunni, rb_classi WHERE rb_classi.id_classe = rb_alunni.id_classe AND attivo = '1' AND rb_alunni.id_alunno NOT IN (SELECT rb_genitori_figli.id_alunno FROM rb_genitori_figli) ORDER BY sezione, anno_corso, rb_alunni.cognome, rb_alunni.nome";
//print $sel_alunni;
$res_alunni = $db->execute($sel_alunni);
$num_alunni = $res_alunni->num_rows;
while($alunno = $res_alunni->fetch_assoc()){
	if (isset($classi[$alunno['id_classe']])){
		$classi[$alunno['id_classe']]['alunni'][] = $alunno;
	}
	else {
		$num_alunni--;
	}
}
$num_righe = intval(($num_alunni + 1) / 2);

$lb = "";
if (isset($_GET['school_order'])) {
	switch ($_GET['school_order']) {
		case 1:
			$lb = "scuola secondaria";
			break;
		case 2:
			$lb = "scuola primaria";
			break;
		case 3:
			$lb = "scuola dell'infanzia";
			break;
	}
}

$navigation_label = "statistiche registro";
$drawer_label = "Genitori $lb non registrati (". $num_alunni." totali)";

include "stat_reg_parents.html.php";
