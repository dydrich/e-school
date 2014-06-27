<?php

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if(!isset($_REQUEST['cls'])){
	$_REQUEST['cls'] = $_SESSION['__classe__']->get_ID();
}
else{
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

$prove = array();
$labels = array();
$vars = array();
$totali_classe = array();
if (isset($_REQUEST['__goals__']) && $_REQUEST['__goals__'] == 1) {
	
}
else {
	$selected = array();
	if (isset($_SESSION['__user_config__']['tipologia_prove'])){
		$selected = $_SESSION['__user_config__']['tipologia_prove'];
	}
	if (count($selected) > 0){
		$sel_prove = "SELECT * FROM rb_tipologia_prove WHERE id IN (".join(",", $selected).")";
	}
	else{
		$sel_prove = "SELECT * FROM rb_tipologia_prove WHERE `default` = 1";
	}
	try {
		$res_prove = $db->executeQuery($sel_prove);
	} catch (MySQLException $ex){
		$ex->redirect();
		exit;
	}
	while ($row = $res_prove->fetch_assoc()){
		$prove[$row['id']] = $row;
		$labels[$row['id']] = $row['label'];
		$vars[$row['id']] = array("num_prove" => 0, "somma" => 0, "media" => 0);
		$totali_classe[$row['id']] = array("num_prove" => 0, "somma" => 0, "media" => 0, "num_alunni" => 0);
		$num_alunni[$row['id']] = 0;
	}
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	if(date("Ymd") > $fine_q){
		$q = 2;
	}
	else{
		$q = 1;
	}
}
	
switch($q){
	case 0:
		$int_time = "AND data_voto < NOW()";
		$note_time = "AND data < NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_voto <= '".$fine_q."'";
		$note_time = "AND data <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto  > '".$fine_q."' AND data_voto <= NOW()) ";
		$note_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE rb_alunni.id_classe = ".$_REQUEST['cls']." AND attivo = '1' ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}
$numero_alunni = $res_alunni->num_rows;
while(list($k, $v) = each($totali_classe)){
	$totali_classe[$k]['num_alunni'] = $numero_alunni;
}

$mat = $_SESSION['__user__']->getSubject();
/*
$sel_mat = "SELECT * FROM rb_materie WHERE id_materia = $mat";
try {
	$res_mat = $db->executeQuery($sel_mat);
} catch (MySQLException $ex) {
	$ex->redirect();
}
$_materia = $res_mat->fetch_assoc();
if($_materia['idpadre'] == "" && $_materia['has_sons'] > 0 && $_materia['pagella'] == 0){
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE (rb_cdc.id_materia = rb_materie.idpadre) AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid()." AND rb_cdc.id_classe = ". $_REQUEST['cls'] ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND id_anno = ".$_SESSION['__current_year__']->get_ID();
	print $sel_materie;
}

if($mat == 12){
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE rb_cdc.id_docente = ".$_SESSION['__user__']->getUid()." AND ((rb_materie.idpadre = ".$mat." AND has_sons = 0) OR (rb_materie.idpadre = 2)) AND rb_cdc.id_classe = ". $_REQUEST['cls'] ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND id_anno = ".$_SESSION['__current_year__']->get_ID()." GROUP BY rb_materie.id_materia, materia";
}
else if($mat == 7){
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE (rb_cdc.id_materia = rb_materie.idpadre) AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid()." AND rb_cdc.id_classe = ". $_REQUEST['cls'] ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND id_anno = ".$_SESSION['__current_year__']->get_ID();
}
else{
	$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid()." AND rb_cdc.id_classe = ". $_REQUEST['cls'] ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND id_anno = ".$_SESSION['__current_year__']->get_ID();
}
*/
$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid()." AND rb_cdc.id_classe = ". $_REQUEST['cls'] ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND pagella = 1 AND id_anno = ".$_SESSION['__current_year__']->get_ID();
//print $sel_materie;
try{
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex){
	$ex->redirect();
}
$materie = array();
while($mt = $res_materie->fetch_assoc()){
	$materie[] = array("id" => $mt['id_materia'], "mat" => $mt['materia']);
}
$_SESSION['__subjects__'] = $materie;
$_SESSION['__materia__'] = $materie[0]['id'];

$uid = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$goals = array();
if (isset($_REQUEST['__goals__']) && $_REQUEST['__goals__'] == 1) {
	$query = "SELECT rb_obiettivi.* FROM rb_obiettivi LEFT JOIN rb_obiettivi_classe ON rb_obiettivi.id = id_obiettivo WHERE docente = {$uid} AND rb_obiettivi.anno = {$anno} AND rb_obiettivi_classe.classe = {$_REQUEST['cls']} AND materia = {$_REQUEST['subject']} ORDER BY id_padre";
	try {
		$res_goals = $db->executeQuery($query);
	} catch (MySQLException $ex) {
		$ex->redirect();
	}
	while ($row = $res_goals->fetch_assoc()){
		if (!$goals[$row['id']]){
			$goals[$row['id']] = $row;
		}
		$totali_classe[$row['id']] = array("num_prove" => 0, "somma" => 0, "media" => 0, "num_alunni" => $numero_alunni);
		$vars[$row['id']] = array("num_prove" => 0, "somma" => 0, "media" => 0);
		
		if ($row['id_padre'] != ""){
			unset($goals[$row['id_padre']]);
			unset($totali_classe[$row['id_padre']]);
			unset($vars[$row['id_padre']]);
		}
	}
}

/*
 * calcolo del numero di colonne e relativa lunghezza
*/
if (isset($_REQUEST['__goals__']) && $_REQUEST['__goals__'] == 1 && count($goals) > 0) {
	$_goals = count($goals);
	$tot_col = $_goals + 2;
	$right_cols = $_goals;
	$col_colsp = 2;
	$last_colsp = 1;
	$len = 60 / $_goals;
}
else {
	$tot_col = 7;
	$right_cols = 4;
	$col_colsp = 2;
	$last_colsp = 1;
	$double_len = 20;
	$len = 10;
	if (count($prove) == 1){
		$col_colsp = 4;
		$last_colsp = 2;
		$double_len = 40;
		$len = 20;
	}
	else if (count($prove) == 3){
		$tot_col += 2;
		$right_cols += 2;
		$double_len = 13;
		$len = 6;
	}
}

$change_subject = new ChangeSubject("hid", "", "position: absolute; width: 180px; height: 155px; display: none", "div", $materie);
$change_subject->createLink("text-decoration: none; text-transform: uppercase; font-weight: bold", "left");

if (isset($_REQUEST['__goals__']) && $_REQUEST['__goals__'] == 1) {
	include "index_goals.html.php";
}
else {
	include "index.html.php";
}

?>