<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$classes_table = "rb_classi";
if(isset($_SESSION['__school_order__']) && $_SESSION['__school_order__'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['__school_order__']}";
}
else if(isset($_SESSION['school_order']) && $_SESSION['school_order'] != 0){
	$classes_table = "rb_vclassi_s{$_SESSION['school_order']}";
}

$sel_classi = "SELECT id_classe, anno_corso, sezione, codice, nome FROM {$classes_table}, rb_tipologia_scuola, rb_sedi WHERE id_sede = sede AND {$classes_table}.ordine_di_scuola = id_tipo ORDER BY sezione, anno_corso";
$res_classi = $db->executeQuery($sel_classi);

if(isset($_REQUEST['filtro_classe']) && ($_REQUEST['filtro_classe'] != "")){
	$sel_alunni = "SELECT cognome, nome, id_alunno FROM rb_alunni WHERE id_classe = '".$_REQUEST['filtro_classe']."' ORDER BY cognome, nome";
	//print $sel_alunni;
	try{
		$res_alunni = $db->executeQuery($sel_alunni);
	} catch(MySQLException $ex){
		$ex->alert();
	}
}

$navigation_label = "Area amministrazione: gestione genitori";

include "elenco_alunni.html.php";
