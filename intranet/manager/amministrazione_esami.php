<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 1/17/17
 * Time: 6:24 PM
 * inserimento dati amministrativi per esame di stato
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

$navigation_label = "Scuola secondaria ";
$drawer_label = "Esame di Stato: dati amministrativi";

$data = null;
$res_data = $db->executeQuery("SELECT * FROM rb_dati_amministrativi_esame WHERE anno = ".$_SESSION['__current_year__']->get_ID());
if ($res_data->num_rows > 0) {
	$data = $res_data->fetch_assoc();
	$data['vice_text'] = "";
	$data['segretario_text'] = "";
	if ($data['vice'] != 0) {
		$data['vice_text'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$data['vice']}");
	}
	if ($data['segretario'] != 0) {
		$data['segretario_text'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$data['segretario']}");
	}
}

include "amministrazione_esami.html.php";