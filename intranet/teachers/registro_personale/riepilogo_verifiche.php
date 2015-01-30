<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 25/12/14
 * Time: 19.39
 * riepilogo verifiche con voto alunno per alunno
 */
require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";

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

if (isset($_REQUEST['subject'])) {
	$_SESSION['__materia__'] = $_REQUEST['subject'];
}

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "registro personale";
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	if(date("Y-m-d") > $fine_q){
		$q = 2;
	}
	else{
		$q = 1;
	}
}

switch($q){
	case 0:
	case 3:
		$int_time = "AND data_verifica <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data_verifica <= '".$fine_q."'";
		$label = " primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_verifica  > '".$fine_q."' AND data_verifica <= NOW()) ";
		$label = " secondo quadrimestre";
}

$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE rb_alunni.id_classe = ".$_REQUEST['cls']." AND attivo = '1' ORDER BY cognome, nome";
try{
	$res_alunni = $db->executeQuery($sel_alunni);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunni = array();
while ($row = $res_alunni->fetch_assoc()) {
	$alunni[$row['id_alunno']] = array();
	$alunni[$row['id_alunno']]['data'] = $row;
	$alunni[$row['id_alunno']]['tests'] = array();
}

$sel_tests = "SELECT * FROM rb_verifiche WHERE id_docente = ".$_SESSION['__user__']->getUid()." AND id_classe = ".$_REQUEST['cls']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." AND id_materia = ".$_SESSION['__materia__']." {$int_time} ORDER BY data_verifica";
$res_tests = $db->executeQuery($sel_tests);
if ($res_tests->num_rows > 0) {
	while ($row = $res_tests->fetch_assoc()) {
		foreach ($alunni as $k => $alunno) {
			$alunni[$k]['tests'][$row['id_verifica']] = 0;
		}

		$sel_grades = "SELECT * FROM rb_voti WHERE id_verifica = ".$row['id_verifica'];
		$res_grades = $db->executeQuery($sel_grades);
		while ($list = $res_grades->fetch_assoc()) {
			$alunni[$list['alunno']]['tests'][$list['id_verifica']] = $list['voto'];
		}
	}
}
$res_tests->data_seek(0);

$mat = $_SESSION['__user__']->getSubject();
$sel_materie = "SELECT rb_materie.id_materia, materia FROM rb_materie, rb_cdc WHERE rb_cdc.id_materia = rb_materie.id_materia AND rb_cdc.id_docente = ".$_SESSION['__user__']->getUid(true)." AND rb_cdc.id_classe = ". $_REQUEST['cls'] ." AND (rb_cdc.id_materia = rb_materie.id_materia OR rb_cdc.id_materia = rb_materie.idpadre) AND pagella = 1 AND id_anno = ".$_SESSION['__current_year__']->get_ID();
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
if(count($materie) > 0) {
	$k = 0;
	foreach ($materie as $mt) {
		//print "while";
		if (isset($_REQUEST['subject'])) {
			if ($_REQUEST['subject'] == $mt['id']) {
				$idm = $mt['id'];
				$_mat = $mt['mat'];
			}
		}
		else {
			if (isset($_SESSION['__materia__'])) {
				if ($_SESSION['__materia__'] == $mt['id']) {
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
				else {
					if ($k == 0) {
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
				}
			}
			else {
				if ($k == 0) {
					//print "k==0";
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
			}
		}
		$k++;
	}
	$_SESSION['__materia__'] = $idm;
}

$total_cols = $res_tests->num_rows + 1;
$cols_length = intval(80 / $total_cols);

$drawer_label = "Riepilogo verifiche".$label;

include "riepilogo_verifiche.html.php";
