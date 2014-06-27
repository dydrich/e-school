<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/03/14
 * Time: 18.06
 *
 * stampa il registro del docente di sostegno
 */

require_once "../../lib/start.php";
require_once "../../lib/ArrayMultiSort.php";
require_once "../../lib/TeacherRecordBookManager.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

$navigation_label = "Registro elettronico - Stampa registro personale";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$classi = $_SESSION['__user__']->getClasses();

foreach ($classi as $k => $cl){
	$classi[$k]['studenti'] = array();
	$sel_students = "SELECT id_alunno, nome, cognome FROM rb_alunni, rb_assegnazione_sostegno WHERE rb_alunni.id_alunno = rb_assegnazione_sostegno.alunno AND docente = {$_SESSION['__user__']->getUid()} AND rb_alunni.id_classe = {$k} AND rb_assegnazione_sostegno.anno = {$_SESSION['__current_year__']->get_ID()} ORDER BY cognome, nome";
	$res_students = $db->executeQuery($sel_students);
	if ($res_students->num_rows > 0){
		while ($row = $res_students->fetch_assoc()){
			$classi[$k]['studenti'][$row['id_alunno']] = $row['cognome']." ".$row['nome'];
		}
	}
}

include_once 'stampa_registro_sostegno.html.php';