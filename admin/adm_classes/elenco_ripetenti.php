<?php

/*
 * elenco dei ripetenti non ancora assegnati ad alcuna classe
 * permette l'assegnazione alle classi terze
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$classes_table = "rb_classi";
if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
}
else if(isset($_GET['school_order']) && $_GET['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_GET['school_order']}";
	$_SESSION['__school_order__'] = $_GET['school_order'];
}

$sel_alunni = "SELECT id_alunno, cognome, nome FROM rb_alunni WHERE id_classe IS NULL AND ripetente = 1 AND attivo = '1' ORDER BY cognome, nome";
$res_alunni = $db->execute($sel_alunni);

$sel_classi = "SELECT CONCAT_WS(' ', anno_corso, sezione) AS classe, id_classe, {$classes_table}.ordine_di_scuola, nome FROM {$classes_table}, rb_sedi WHERE sede = id_sede ORDER BY sezione, anno_corso ";
$res_classi = $db->executeQuery($sel_classi);

$navigation_label = "Area amministrazione: gestione classi";

include_once 'elenco_ripetenti.html.php';