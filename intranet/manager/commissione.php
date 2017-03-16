<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 3/1/17
 * Time: 6:57 PM
 */
require_once "../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";

$anno = $_SESSION['__current_year__']->get_ID();

$id_comm = $_REQUEST['idc'];

$res_comm = $db->executeQuery("SELECT * FROM rb_commissioni_esame WHERE id_commissione = $id_comm");
$commissione = $res_comm->fetch_assoc();

$docenti = [];
$sel_teachers = "SELECT nome, cognome, uid FROM rb_utenti, rb_docenti_commissione_esame WHERE docente = uid AND commissione = {$id_comm}";
$res_teachers = $db->executeQuery($sel_teachers);
while ($row = $res_teachers->fetch_assoc()) {
	$docenti[$row['uid']] = $row;
	$docenti[$row['uid']]['materie'] = "";
	/*
	 * recupero le materie
	 */
	$res_subjects = $db->executeQuery("SELECT materia 
									  FROM rb_materie, rb_cdc 
									  WHERE rb_cdc.id_materia = rb_materie.id_materia 
									  AND id_anno = $anno 
									  AND rb_cdc.id_docente = {$row['uid']}
									  AND rb_cdc.id_classe = {$commissione['classe']}");
	$mat = [];
	while ($r = $res_subjects->fetch_assoc()) {
		$mat[] = $r['materia'];
	}
	$docenti[$row['uid']]['materie'] = implode(", ", $mat);
}

$drawer_label = "Esame di Stato: sottocommissione n. {$commissione['numero']} (classe 3{$commissione['sezione']})";

include "commissione.html.php";