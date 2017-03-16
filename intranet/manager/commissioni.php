<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 2/28/17
 * Time: 4:16 PM
 */
require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Esame di Stato: commissione e sottocommissioni";

$anno = $_SESSION['__current_year__']->get_ID();

/*
 * controllo se le commissioni sono state inserite (al primo accesso vanno inserite)
 */
$sel_commissioni = "SELECT * FROM rb_commissioni_esame WHERE anno = $anno";
$res_commissioni = $db->executeQuery($sel_commissioni);
$create = false;
if($res_commissioni->num_rows < 1) {
	$create = true;
}
else {
	/*
	 * recupero i dati delle commissioni
	 */
	$commissione = [];
	$cls = [];

	$sel_comm = "SELECT * FROM rb_commissioni_esame WHERE anno = $anno ORDER BY numero";
	$res_comm = $db->executeQuery($sel_comm);
	while ($comm = $res_comm->fetch_assoc()) {
		$cls[$comm['id_commissione']] = $comm;
		$cls[$comm['id_commissione']]['cdc'] = [];

		/*
		 * recupero docenti
		 */
		$sel_teachers = "SELECT nome, cognome, uid FROM rb_utenti, rb_docenti_commissione_esame WHERE docente = uid AND commissione = {$comm['id_commissione']}";
		$res_teachers = $db->executeQuery($sel_teachers);
		while ($row = $res_teachers->fetch_assoc()) {
			$cls[$comm['id_commissione']]['cdc'][$row['uid']] = $row;
			if (!isset($commissione[$row['uid']])) {
				$commissione[$row['uid']] = $row;
			}
		}
	}
	$ams = new ArrayMultiSort($commissione);
	$ams->setSortFields(array('cognome'));
	$ams->sort();
	$commissione = $ams->getData();
}

include "commissioni.html.php";
