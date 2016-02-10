<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 09/02/16
 * Time: 12.27
 */
require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$admin_level = getAdminLevel($_SESSION['__user__']);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$classID = $_GET['id'];
$anno = $_SESSION['__current_year__']->get_ID();

$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE id_classe = {$classID} AND sede = id_sede";
try{
	$res_classe = $db->executeQuery($sel_classe);
} catch (MySQLException $ex){
	$ex->alert();
}
$classe = $res_classe->fetch_assoc();

$sel_rap = "SELECT id, CONCAT_WS(' ', rb_genitori.cognome, rb_genitori.nome) AS utente, uid
			FROM rb_genitori, rb_rappresentanti_classe
			WHERE classe = {$classID}
			AND genitore = uid
			ORDER BY utente";
$res_rap = $db->executeQuery($sel_rap);
$raps = [];
while ($row = $res_rap->fetch_assoc()) {
	$raps[] = $row['uid'];
}

$sel_parents = "SELECT CONCAT_WS(' ', rb_genitori.cognome, rb_genitori.nome) AS utente, CONCAT_WS(' ', rb_alunni.cognome, rb_alunni.nome) AS alunno, uid
			FROM rb_alunni, rb_genitori, rb_genitori_figli
			WHERE rb_alunni.id_alunno = rb_genitori_figli.id_alunno
			AND uid = rb_genitori_figli.id_genitore
			AND rb_alunni.id_classe = {$classID}
			ORDER BY utente";
$res_parents = $db->executeQuery($sel_parents);

$navigation_label = "gestione classi";
$drawer_label = "Rappresentanti di classe: ".$classe['anno_corso'].$classe['sezione']." - ". $classe['nome'];

include "rappresentanti_di_classe.html.php";
