<?php
/**
 * inserisce giorno e ora dei colloqui
 * User: riccardo
 * Date: 10/18/16
 * Time: 3:46 PM
 */
require_once '../../lib/start.php';

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$selected = $_SESSION['__user_config__']['tipologia_prove'];

$data = [];
$sel_data = "SELECT valore FROM rb_parametri_utente WHERE id_parametro = 4 AND id_utente = ".$_SESSION['__user__']->getUid();
try {
	$res_data = $db->executeCount($sel_data);
	if($res_data && $res_data != null) {
		$r = explode(";", $res_data);
		$data['day'] = $r[0];
		$data['hour'] = $r[1];
		$data['mandatory'] = $r[2];
		$data['max'] = 0;
		if ($r[2] == 1 && isset($r[3])) {
			$data['max'] = $r[3];
		}
	}
} catch (MySQLException $ex){
	//$ex->redirect();
	echo $sel_data;
	exit;
}

$days = [1 => "Lunedì", 2 => "Martedì", 3 => "Mercoledì", 4 => "Giovedì", 5 => "Venerdì", 6 => "Sabato"];
$hours = [1 => "Prima", 2 => "Seconda", 3 => "Terza", 4 => "Quarta", 5 => "Quinta"];

$navigation_label = "registro elettronico ";
$drawer_label = "Data e ora dei colloqui quindicinali";

include "colloqui.html.php";