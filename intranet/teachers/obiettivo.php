<?php

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "Registro elettronico - Gestione obiettivi didattici";

$label = "Modifica obiettivo";
$action = UPDATE_OBJECT;
if ($_GET['oid'] == 0){
	$label = "Nuovo obiettivo";
	$action = INSERT_OBJECT;
}
else {
	$oid = $_GET['oid'];
	$query = "SELECT rb_obiettivi.*, classe, CONCAT(anno_corso, sezione) AS dcls FROM rb_obiettivi LEFT JOIN rb_obiettivi_classe ON rb_obiettivi.id = id_obiettivo, rb_classi WHERE rb_obiettivi.id = {$oid} AND rb_obiettivi_classe.classe = id_classe ORDER BY id_padre";
	$res = $db->executeQuery($query);
	$goal = array();
	$i = 0;
	while ($row = $res->fetch_assoc()){
		if ($i == 0){
			$goal = $row;
		}
		if (!isset($goal['classi'])){
			$goal['classi'] = array();
		}
		$goal['classi'][$row['classe']] = $row['dcls'];
		$i++;
	}
}

$subject = $_SESSION['__user__']->getSubject();
$uid = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$ordine_di_scuola = $_SESSION['__user__']->getSchoolOrder();
$classes = $_SESSION['__user__']->getClasses();

if ($ordine_di_scuola == 2){
	$query = "SELECT * FROM rb_materie WHERE idpadre = {$subject}";
}
else if ($subject == 12 || $subject == 9) {
	$query = "SELECT * FROM rb_materie WHERE idpadre = {$subject}";
}
else {
	$query = "SELECT * FROM rb_materie WHERE id_materia = {$subject}";
}
$res_materie = $db->execute($query);

$sel_obj = "SELECT * FROM rb_obiettivi WHERE id_padre IS NULL AND docente = {$uid} AND rb_obiettivi.anno = {$anno} ORDER BY id";
$res_obj = $db->execute($sel_obj);

include "obiettivo.html.php";
