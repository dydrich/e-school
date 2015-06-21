<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 20/06/15
 * Time: 18.24
 * stampa registri della programmazione
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);
$drawer_label = "Stampa registri della programmazione";

$classi = array();
$moduli = array();
$classi_associate = array();

try {
	$res_classes = $db->executeQuery("SELECT * FROM rb_classi WHERE ordine_di_scuola = 2 ORDER BY anno_corso, sezione");
	while ($c = $res_classes->fetch_assoc()) {
		$classi[$c['id_classe']] = $c['anno_corso'].$c['sezione'];
	}
	$res_mod = $db->executeQuery("SELECT * FROM rb_moduli_primaria LEFT JOIN rb_registri_programmazione ON id = modulo WHERE anno = ".$_SESSION['__current_year__']->get_ID());
	if ($res_mod->num_rows > 0) {
		$res_cls_mod = $db->executeQuery("SELECT rb_classi_modulo.* FROM rb_classi_modulo, rb_moduli_primaria WHERE rb_classi_modulo.id_modulo = rb_moduli_primaria.id AND anno = ".$_SESSION['__current_year__']->get_ID());
		while ($row = $res_mod->fetch_assoc()) {
			$moduli[$row['id']]['data'] = $row;
			$moduli[$row['id']]['classi'] = array();
		}
		if ($res_cls_mod->num_rows > 0) {
			while ($r = $res_cls_mod->fetch_assoc()) {
				$moduli[$r['id_modulo']]['classi'][] = $r['classe'];
				$classi_associate[] = $r['classe'];
			}
		}
	}
} catch (MySQLException $ex) {
	$ex->redirect();
}

include "stampa_registri_programmazione.html.php";
