<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 04/09/14
 * Time: 17.38
 */
require_once "../../lib/start.php";
require_once "../../lib/ScheduleModule.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

ini_set("display_errors", DISPLAY_ERRORS);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = getAdminLevel($_SESSION['__user__']);

$classi = array();
$moduli = array();
$classi_associate = array();

try {
	$res_classes = $db->executeQuery("SELECT * FROM rb_classi WHERE ordine_di_scuola = 2 ORDER BY anno_corso, sezione");
	while ($c = $res_classes->fetch_assoc()) {
		$classi[$c['id_classe']] = $c['anno_corso'].$c['sezione'];
	}
	$res_mod = $db->executeQuery("SELECT * FROM rb_moduli_primaria WHERE anno = ".$_SESSION['__current_year__']->get_ID());
	if ($res_mod->num_rows > 0) {
		$res_cls_mod = $db->executeQuery("SELECT rb_classi_modulo.* FROM rb_classi_modulo, rb_moduli_primaria WHERE rb_classi_modulo.id_modulo = rb_moduli_primaria.id AND anno = ".$_SESSION['__current_year__']->get_ID());
		while ($row = $res_mod->fetch_assoc()) {
			$moduli[$row['id']] = array();
		}
		if ($res_cls_mod->num_rows > 0) {
			while ($r = $res_cls_mod->fetch_assoc()) {
				$moduli[$r['id_modulo']][] = $r['classe'];
				$classi_associate[] = $r['classe'];
			}
		}
	}
} catch (MySQLException $ex) {
	$ex->redirect();
}

$navigation_label = "Area amministrazione: gestione classi";

include "moduli_primaria.html.php";
