<?php

require_once "../../lib/start.php";

check_session(FAKE_WINDOW);
check_permission(ADM_PERM|AIS_PERM|AMS_PERM|APS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$admin_level = 0;

$offset = 0;
if (isset($_REQUEST['offset'])){
	$offset = $_REQUEST['offset'];
}

$materie = array();
$sel_sub = "SELECT * FROM rb_materie WHERE id_materia <> 1 ORDER BY id_materia";
$res_sub = $db->executeQuery($sel_sub);
$_i = $_REQUEST['id'];
while($m = $res_sub->fetch_assoc()) {
	$materie[$m['id_materia']] = $m;
	if($_REQUEST['id'] == $m['id_materia']) {
		$subject = $m;
	}
}

$sel_tipologie = "SELECT * FROM rb_tipologia_scuola WHERE has_admin = 1 AND attivo = 1";
$res_tipologie = $db->executeQuery($sel_tipologie);

$navigation_label = "gestione scuola";
$drawer_label = "Dettaglio materia";

include "dettaglio_materia.html.php";
