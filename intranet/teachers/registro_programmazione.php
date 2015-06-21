<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/09/14
 * Time: 16.58
 */

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "registro elettronico";
$drawer_label = "Registro della programmazione";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];

$modulo = $_REQUEST['module'];
$_SESSION['fs_module'] = $modulo;

try {
	$res_mod = $db->executeQuery("SELECT * FROM rb_riunioni_programmazione WHERE id_modulo = $modulo ORDER BY data DESC");
	$res_reg = $db->executeQuery("SELECT * FROM rb_registri_programmazione WHERE modulo = $modulo");
} catch (MySQLException $ex) {
	$ex->redirect();
}
$riunioni = array();
while ($row = $res_mod->fetch_assoc()) {
	list ($y, $m, $d) = explode("-", $row['data']);
	$m = intval($m);
	if (!isset($riunioni[$m])) {
		$riunioni[$m] = array();
	}
	$riunioni[$m][] = $row;
}

$res_classes = $db->executeQuery("SELECT CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS desc_cls FROM rb_classi_modulo, rb_classi WHERE id_classe = classe AND id_modulo = ".$modulo);
$classes = array();
while ($res_cls = $res_classes->fetch_assoc()) {
	$classes[] = $res_cls['desc_cls'];
}

$path = "../../download/registri/".$_SESSION['__current_year__']->get_descrizione()."/scuola_primaria/programmazione/modulo".implode("-", $classes)."/";
if ($res_reg->num_rows > 0) {
	$reg = $res_reg->fetch_assoc();
	$dt = $reg['data_creazione'];
	$date = format_date(substr($dt, 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/");
	$time = substr($dt, 11, 5);
	$file = $path.$reg['file'];
	$string_date = "(ultima modifica il ".$date." alle ore ".$time.")";
}

setlocale(LC_TIME, "it_IT.utf8");

include "registro_programmazione.html.php";
