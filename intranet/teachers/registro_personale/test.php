<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

setlocale(LC_TIME, "it_IT");

$sel_test = "SELECT rb_verifiche.*, rb_tipologia_prove.tipologia AS tipo FROM rb_verifiche, rb_tipologia_prove WHERE id_verifica = {$_REQUEST['idt']} AND rb_verifiche.tipologia = rb_tipologia_prove.id";
$res_test = $db->execute($sel_test);
$test = $res_test->fetch_assoc();
$giorno_str = strftime("%A %d %B %H:%M", strtotime($test['data_verifica']));

$sel_stud = "SELECT nome, cognome, id_alunno FROM rb_alunni WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY cognome, nome";
$res_stud = $db->execute($sel_stud);
$alunni = array();
while($alunno = $res_stud->fetch_assoc()){
	$alunni[$alunno['id_alunno']] = $alunno;
	$alunni[$alunno['id_alunno']]['voto'] = 0;
	$alunni[$alunno['id_alunno']]['id_voto'] = 0;
}

$sel_voti = "SELECT alunno, voto, id_voto FROM rb_voti WHERE id_verifica = ".$_REQUEST['idt'];
$res_voti = $db->execute($sel_voti);
while($voto = $res_voti->fetch_assoc()){
	$alunni[$voto['alunno']]['voto'] = $voto['voto'];
	$alunni[$voto['alunno']]['id_voto'] = $voto['id_voto'];
}

$sel_sub = "SELECT * FROM rb_materie WHERE id_materia = ".$_SESSION['__materia__'];
$res_sub = $db->execute($sel_sub);
$materia = $res_sub->fetch_assoc();

$sel_alunni = "SELECT COUNT(alunno) FROM rb_voti WHERE id_verifica = ".$_REQUEST['idt'];
$count_alunni = $db->executeCount($sel_alunni);
$avg = "-";
if($count_alunni > 0){
	$sel_avg = "SELECT AVG(voto) FROM rb_voti WHERE id_verifica = ".$_REQUEST['idt'];
	$avg = $db->executeCount($sel_avg);
}
$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
//echo $avg;
if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
	if($avg < 5.5){
		$avg = 4;
	}
	else if ($avg > 6.49 && $avg < 8){
		$avg = 8;
	}
}

$selected = $_SESSION['__user_config__']['tipologia_prove'];
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

$sel_obj = "SELECT nome FROM rb_obiettivi, rb_obiettivi_verifica WHERE id_obiettivo = rb_obiettivi.id AND id_verifica = {$_REQUEST['idt']}";
$res_obj = $db->executeQuery($sel_obj);
$obj = array();
while ($row = $res_obj->fetch_assoc()){
	$obj[] = $row['nome'];
}
$string_obj = "";
$string_obj = implode(" || ", $obj);

$navigation_label = "Registro personale del docente - Classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();

include "test.html.php";

?>