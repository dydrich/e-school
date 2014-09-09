<?php

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "Registro elettronico - Gestione obiettivi didattici";

$subject = $_SESSION['__user__']->getSubject();
$uid = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$ordine_di_scuola = $_SESSION['__user__']->getSchoolOrder();

$goals = array();

if ($subject == 12) {
	$query = "SELECT rb_obiettivi.*, classe, CONCAT(anno_corso, sezione) AS dcls FROM rb_obiettivi LEFT JOIN rb_obiettivi_classe ON rb_obiettivi.id = id_obiettivo, rb_classi WHERE docente = {$uid} AND rb_obiettivi.anno = {$anno} AND rb_obiettivi_classe.classe = id_classe ORDER BY id_padre";
	//echo $query;
	try {
		$res = $db->executeQuery($query);
	} catch (MySQLException $ex) {
		$ex->redirect();
	}
	while ($row = $res->fetch_assoc()){
		if (!isset($goals[$row['materia']])){
			$goals[$row['materia']] = array();
		}
		if ($row['id_padre'] == ""){
			if (!isset($goals[$row['materia']][$row['id']])){
				$goals[$row['materia']][$row['id']] = $row;
			}
			if (!isset($goals[$row['materia']][$row['id']]['classi'])){
				$goals[$row['materia']][$row['id']]['classi'] = array();
			}
			$goals[$row['materia']][$row['id']]['classi'][] = $row['dcls'];
		}
		else {
			if (!isset($goals[$row['materia']][$row['id_padre']]['children'])){
				$goals[$row['materia']][$row['id_padre']]['children'] = array();
			}
			if (!isset($goals[$row['materia']][$row['id_padre']]['children'][$row['id']])){
				$goals[$row['materia']][$row['id_padre']]['children'][$row['id']] = $row;
			}
			if (!isset($goals[$row['materia']][$row['id_padre']]['children'][$row['id']]['classi'])){
				$goals[$row['materia']][$row['id_padre']]['children'][$row['id']]['classi'] = array();
			}
			$goals[$row['materia']][$row['id_padre']]['children'][$row['id']]['classi'][] = $row['dcls'];
		}
	}
}
else if ($subject == 9) {
	
}
else {
	$query = "SELECT rb_obiettivi.*, classe, CONCAT(anno_corso, sezione) AS dcls FROM rb_obiettivi LEFT JOIN rb_obiettivi_classe ON rb_obiettivi.id = id_obiettivo, rb_classi WHERE docente = {$uid} AND rb_obiettivi.anno = {$anno} AND rb_obiettivi_classe.classe = id_classe ORDER BY id_padre";
	try {
		$res = $db->executeQuery($query);
	} catch (MySQLException $ex) {
		$ex->redirect();
	}
	while ($row = $res->fetch_assoc()){
		if ($row['id_padre'] == ""){
			if (!isset($goals[$row['id']])){
				$goals[$row['id']] = $row;
			}
			if (!isset($goals[$row['id']]['classi'])){
				$goals[$row['id']]['classi'] = array();
			}
			$goals[$row['id']]['classi'][] = $row['dcls'];
		}
		else {
			if (!isset($goals[$row['id_padre']]['children'])){
				$goals[$row['id_padre']]['children'] = array();
			}
			if (!isset($goals[$row['id_padre']]['children'][$row['id']])){
				$goals[$row['id_padre']]['children'][$row['id']] = $row;
			}
			if (!isset($goals[$row['id_padre']]['children'][$row['id']]['classi'])){
				$goals[$row['id_padre']]['children'][$row['id']]['classi'] = array();
			}
			$goals[$row['id_padre']]['children'][$row['id']]['classi'][] = $row['dcls'];
		}
	}
}

include "obiettivi.html.php";
