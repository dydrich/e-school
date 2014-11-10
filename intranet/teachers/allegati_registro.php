<?php

require_once "../../lib/start.php";
require_once "../../lib/MimeType.php";
require_once "../../lib/TeacherRecordBookManager.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

ini_set("display_errors", "1");

$navigation_label = "registro elettronico ";
$drawer_label = "Registro personale: documenti allegati";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$school_order_directory = "scuola_media";
if ($ordine_scuola == 2){
	$school_order_directory = "scuola_primaria";
}

$user_directory = $_SESSION['__user__']->getFullName();
$user_directory = preg_replace("/ /", "_", $user_directory);
$user_directory = strtolower($user_directory);

/*
 * registro docente curricolare
 */
if (isset($_REQUEST['sub'])){
	$_GET['std'] = 0;
	$desc = $db->executeCount("SELECT materia FROM rb_materie WHERE id_materia = {$_GET['sub']}");
	$desc_cls = $db->executeCount("SELECT CONCAT(anno_corso, sezione) FROM rb_classi WHERE id_classe = {$_GET['cls']}");

	$sel_registro = "SELECT * FROM rb_registri_personali WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$_SESSION['__user__']->getUid()} AND classe = {$_GET['cls']} AND materia = {$_GET['sub']}";
	$res_registro = $db->execute($sel_registro);
	$allegati = array();
	$hasLog = true;
	if ($res_registro->num_rows > 0){
		$registro = $res_registro->fetch_assoc();
		$sel_allegati = "SELECT * FROM rb_allegati_registro_docente WHERE registro = {$registro['id']}";
		$res_allegati = $db->execute($sel_allegati);
		while ($all = $res_allegati->fetch_assoc()){
			$allegati[] = $all;
		}
	}
	else {
		$hasLog = false;
		$db->executeUpdate($q = "INSERT INTO rb_registri_personali (anno, docente, classe, materia, file, data_creazione) VALUES ({$_SESSION['__current_year__']->get_ID()}, {$_SESSION['__user__']->getUid()}, {$_GET['cls']}, {$_GET['sub']}, NULL, NULL)");
		$sel_registro = "SELECT * FROM rb_registri_personali WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$_SESSION['__user__']->getUid()} AND classe = {$_GET['cls']} AND materia = {$_GET['sub']}";
		$res_registro = $db->execute($sel_registro);
		$registro = $res_registro->fetch_assoc();
	}
}
/*
 * docente di sostegno
 */
else if (isset($_REQUEST['std'])){
	$_GET['sub'] = 0;
	$sel_std = "SELECT CONCAT_WS(' ', cognome, nome) AS studente FROM rb_alunni WHERE id_alunno = {$_REQUEST['std']}";
	$desc = $db->executeCount($sel_std);
	$desc_cls = $db->executeCount("SELECT CONCAT(anno_corso, sezione) FROM rb_classi WHERE id_classe = {$_GET['cls']}");

	$sel_registro = "SELECT * FROM rb_registri_personali WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$_SESSION['__user__']->getUid()} AND classe = {$_GET['cls']} AND alunno = {$_GET['std']}";
	$res_registro = $db->execute($sel_registro);
	$allegati = array();
	$hasLog = true;
	if ($res_registro->num_rows > 0){
		$registro = $res_registro->fetch_assoc();
		$sel_allegati = "SELECT * FROM rb_allegati_registro_docente WHERE registro = {$registro['id']}";
		$res_allegati = $db->execute($sel_allegati);
		while ($all = $res_allegati->fetch_assoc()){
			$allegati[] = $all;
		}
	}
	else {
		$hasLog = false;
		$db->executeUpdate($q = "INSERT INTO rb_registri_personali (anno, docente, classe, materia, file, data_creazione, alunno) VALUES ({$_SESSION['__current_year__']->get_ID()}, {$_SESSION['__user__']->getUid()}, {$_GET['cls']}, NULL, NULL, NULL, {$_GET['std']})");
		$sel_registro = "SELECT * FROM rb_registri_personali WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$_SESSION['__user__']->getUid()} AND classe = {$_GET['cls']} AND alunno = {$_GET['std']}";
		$res_registro = $db->execute($sel_registro);
		$registro = $res_registro->fetch_assoc();
	}
}

$registro['allegati'] = $allegati;
$_SESSION['registro'] = $registro;

$dir_path = $_SESSION['__config__']['document_root']."/rclasse/download/registri/".$_SESSION['__current_year__']->get_descrizione()."/{$school_order_directory}/docenti/{$user_directory}";
if (!file_exists($dir_path)){
	mkdir($dir_path, 0755, true);
}

include "allegati_registro.html.php";
