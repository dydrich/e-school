<?php 

// one week
$limit = 6;

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

/*
 * mostra gli argomenti delle lezioni svolte negli ultimi $limit giorni
* ricavandoli dalla tabella reg_firme, contenente le firme dei docenti
* sul registro elettronico. Tale tabella NON contiene indicazioni sulla
* classe, che si trovano nella tabella reg_classi, referenziata da id_registro
*/
$today = date("Y-m-d");
if($today > $fine_lezioni){
	$today = $fine_lezioni;
}

/*
 * date lezioni
*/
$c = intval(date("m"));
if (isset($_GET['view']) && $_GET['view'] == "m"){
	$month = $_GET['m'];
	if ($month == "current"){
		if ($c == 7 || $c == 8){
			$month = 6;
		}
		else{
			$month = $c;
		}
	}
	if ($_GET['f'] == "sub"){
		$qsubject = " AND materia = {$_GET['sub']} ";
	}
	$sel_lessons = "SELECT data, ora, materia, argomento, rb_reg_alunni.ingresso AS ingresso FROM rb_reg_firme, rb_reg_classi, rb_reg_alunni WHERE rb_reg_classi.id_classe = ".$_SESSION['__classe__']->get_ID()." AND rb_reg_firme.id_registro = id_reg AND id_reg = rb_reg_alunni.id_registro AND id_alunno = {$alunno} AND data >= '{$inizio_lezioni}' AND data <= '{$today}' AND DATE_FORMAT(data, '%c') = {$month} {$qsubject} ORDER BY data DESC, ora ASC";
}
else {
	$sel_lessons = "SELECT data, ora, materia, argomento, rb_reg_alunni.ingresso AS ingresso FROM rb_reg_firme, rb_reg_classi, rb_reg_alunni WHERE rb_reg_classi.id_classe = ".$_SESSION['__classe__']->get_ID()." AND rb_reg_firme.id_registro = id_reg AND id_reg = rb_reg_alunni.id_registro AND id_alunno = {$alunno} AND data >= '{$inizio_lezioni}' AND data <= '{$today}' ORDER BY data DESC, ora ASC";
}
$res_lessons = $db->execute($sel_lessons);
$lezioni = array();
$x = -1;
$dt = '';
while ($row = $res_lessons->fetch_assoc()){
	if ($dt != $row['data']){
		$x++;
		$dt = $row['data'];
		$lezioni[$x]['data'] = $row['data'];
		$lezioni[$x]['assente'] = false;
		if ($row['ingresso'] == ""){
			$lezioni[$x]['assente'] = true;
		}
		$lezioni[$x]['ore'] = array();
	}
	$lezioni[$x]['ore'][$row['ora']] = $row;
}

if (isset($_GET['view']) && $_GET['view'] == "m"){
	$previous = $month - 1;
	$next = $month + 1;
	if ($next > 12) $next = 1;
	if ($previous < 1) $previous = 12;
	if ($month == 6) $next = null;
	if ($month == 9) $previous = null;
	$start = 0;
	$max = count($lezioni);
	$mesi_scuola = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");
	$num_mesi_scuola = array(9, 10, 11, 12, 1, 2, 3, 4, 5, 6);
	$mesi = array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre", );
	/*
	 * barra di navigazione tra i mesi
	*/
	$min_m = 0;
	if ($c > 5 && $c < 9){
		$max_m = 9;
	}
	else if ($c > 8){
		$max_m = $c - 9;
	}
	else {
		$max_m = $c + 3;
	}
}
else {
	$offset = 0;
	if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}

	$pages = ceil(count($lezioni) / $limit);
	if($pages < 1){
		$pages = 1;
	}
	if($offset == 0){
		$page = 1;
	}
	else{
		$page = ($offset / $limit) + 1;
	}

	$start = $offset;
	$max = $offset + $limit;
	if ($max > count($lezioni)){
		$max = count($lezioni);
	}

	$next = $max;
	if ($next > count($lezioni)){
		$next = "";
	}
	$previous = $offset - $limit;
	if ($previous < 0){
		$previous = 0;
	}
	$last = ($pages - 1) * $limit;
}

$materie = array();
$comportamento = $sostegno = 0;
if ($ordine_scuola == 1){
	$sostegno = 27;
}
else {
	$sostegno = 41;
	$comportamento = 40;
}
$sel_materie = "SELECT * FROM rb_materie WHERE id_materia > 2 AND tipologia_scuola = {$ordine_scuola} AND pagella = 1 AND id_materia <> {$comportamento}";
$res_materie = $db->execute($sel_materie);
while($mat = $res_materie->fetch_assoc()){
	$materie[$mat['id_materia']] = $mat['materia'];
}

include "lessons.html.php";
