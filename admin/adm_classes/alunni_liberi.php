<?php

/**
 * elenco degli alunni non assegnati ad alcuna classe
* 
*/

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$classes_table = "rb_classi";

$sel_params = "";
if(isset($_REQUEST['lettera']))
	$sel_params = "AND cognome LIKE '".$db->real_escape_string($_REQUEST['lettera'])."%'";

$sel_alunni = "SELECT id_alunno, cognome, nome FROM rb_alunni WHERE attivo = '1' AND id_classe IS NULL $sel_params ORDER BY cognome, nome";
$res_alunni = $db->execute($sel_alunni);

$sel_classi = "SELECT CONCAT_WS(' ', anno_corso, sezione) AS classe, id_classe, {$classes_table}.ordine_di_scuola, nome FROM {$classes_table}, rb_sedi WHERE sede = id_sede ORDER BY sezione, anno_corso ";
$res_classi = $db->executeQuery($sel_classi);

$alpha = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

$navigation_label = "gestione classi";
$drawer_label = "Elenco alunni non assegnati alle classi (estratti <span id='st_count'>". $res_alunni->num_rows."</span> alunni)";

include_once 'alunni_liberi.html.php';
