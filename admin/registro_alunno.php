<?php

/*
 * inserisce i record del registro elettronico per un nuovo alunno, o per un alunno
 * tresferito in una nuova classe della stessa scuola
 */

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: text/plain");

$classe = $_REQUEST['classe'];
$old_class = $_REQUEST['old'];
$id_anno = $_SESSION['__current_year__']->get_ID();
$alunno = $_REQUEST['sid'];
$action = $_REQUEST['action'];

$orari = array();
$sel_registro = "SELECT id_reg, ingresso, uscita, data FROM rb_reg_classi WHERE id_classe = $classe AND id_anno = $id_anno ORDER BY id_reg ";
$res_registro = $db->executeQuery($sel_registro);
while($day = $res_registro->fetch_assoc()){
	$orari[$day['data']] = array();
	$orari[$day['data']]['new_id_registro'] = $day['id_reg'];
	$orari[$day['data']]['new_enter'] = $day['ingresso'];
	$orari[$day['data']]['new_exit'] = $day['uscita'];
	$insert_al = "INSERT INTO rb_reg_alunni VALUES (".$day['id_reg'].", $alunno, '".$day['ingresso']."', '".$day['uscita']."', NULL, NULL)";
	try{
		$db->executeUpdate($insert_al);
	} catch (MySQLException $ex){
		$ex->alert();
		exit;
	}
}

if($action == "transfer"){
	// raccolta orari classe precedente
	$sel_old_registro = "SELECT id_reg, ingresso, uscita, data FROM rb_reg_classi WHERE id_classe = $old_class AND id_anno = $id_anno ORDER BY id_reg ";
	$res_old_registro = $db->executeQuery($sel_old_registro);
	while($old_day = $res_old_registro->fetch_assoc()){
		$orari[$old_day['data']]['old_id_registro'] = $old_day['id_reg'];
		$orari[$old_day['data']]['old_enter'] = $old_day['ingresso'];
		$orari[$old_day['data']]['old_exit'] = $old_day['uscita'];
	}
	// assenze
	$sel_abs = "SELECT data FROM rb_reg_classi, rb_reg_alunni WHERE id_reg = id_registro AND id_classe = $old_class AND id_anno = $id_anno AND id_alunno = $alunno AND entrata IS NULL";
	$res_abs = $db->executeQuery($sel_abs);
	while($dt = $res_abs->fetch_assoc()){
		$db->executeUpdate("UPDATE rb_reg_alunni SET ingresso = NULL, uscita = NULL WHERE id_registro = ".$orari[$dt['data']]['new_id_registro']);
	}
}

print "ok";
exit;