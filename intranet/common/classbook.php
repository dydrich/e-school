<?php

// numero di giorni da visualizzare
$limit = 12;

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$today = date("Y-m-d");
if (isset($_GET['month'])){
	$month = $_GET['month'];
}
else {
	$month = intval(date("m"));
}
if($today > $fine_lezioni){
	$today = $fine_lezioni;
	if (!isset($_GET['month'])){
		$month = 6;
	}
}
$previous = $month - 1;
$next = $month + 1;
if ($next > 12) $next = 1;
if ($previous < 1) $previous = 12;
if ($month == 6) $next = null;
if ($month == 9) $previous = null;

$sel_orario_alunno = "SELECT data, rb_reg_alunni.ingresso, rb_reg_alunni.uscita, note, id_alunno, giustificata FROM rb_reg_alunni, rb_reg_classi WHERE id_alunno = {$student_id} AND DATE_FORMAT(data, '%c') = {$month} AND data <= NOW() AND id_anno = {$_SESSION['__current_year__']->get_ID()} AND id_registro = id_reg AND rb_reg_classi.id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY data DESC";
//print $sel_orario_alunno;
$res_orario_alunno = $db->execute($sel_orario_alunno);
setlocale(LC_TIME, "it_IT.utf8");
$mesi_scuola = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");
$mesi = array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre", );
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
window.onload = init_tooltips;

function init_tooltips(){
	var links = document.getElementsByTagName("a");
	for(var i = 0; i < links.length; i++){
		tt = links[i].getAttribute("title");
		if(tt && tt != ""){
			//alert(tt);
			links[i].removeAttribute("title");
			dati = tt.split("|");
			links[i].style.position = "relative";
			tip = document.createElement("span");
			tip.className = "tooltip";
			tip.style.display = "none";
			for (var x = 0; x < dati.length; x++){		
				tip.appendChild(document.createTextNode((x+1)+". "+dati[x]));
				tip.appendChild(document.createElement("br"));
			}
			links[i].appendChild(tip);
		}
	}
}

function show_note(element){ 
	element.getElementsByTagName("span")[0].style.display = "block";
}

function hide_note(element){
	element.getElementsByTagName("span")[0].style.display = "none";
}
</script>
<style type="text/css">
TD{height: 20px}
.tooltip{ 
    position: absolute;
    top: 1em; 
    left: 1em; 
    width: 15em;
    padding: 10px;
    font-size: 1em;
    text-align: center;
    text-decoration: none
}
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php if($area == "genitori") include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Registro di classe, <?php echo $_SESSION['__classe__']->get_anno(),$_SESSION['__classe__']->get_sezione() ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 30%; float: left; position: relative; top: 30%">Data</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Entrata</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Uscita</div>
		<div style="width: 40%; float: left; position: relative; top: 30%">Note</div>
	</div>
	<table style="width: 95%; margin: auto; border-collapse: collapse">
		<tr>
			<td colspan="4" style="font-weight: bold; text-align: center">Mese di <?php echo $mesi[$month - 1] ?></td>
		</tr>
<?php 
while($orario_alunno = $res_orario_alunno->fetch_assoc()){
	$assente = false;
	$giorno_str = strftime("%A", strtotime($orario_alunno['data']));
	if($orario_alunno['ingresso'] == ""){
		$entrata = "A";
		$assente = true;
	}
	else
		$entrata = substr($orario_alunno['ingresso'], 0, 5);
	if($orario_alunno['uscita'] == "")
		$uscita = "A";
	else
		$uscita = substr($orario_alunno['uscita'], 0, 5);
	$background = "";
		
	$add_spaces = false;
	if($entrata == "A" && ($orario_alunno['giustificata'] == 0 || $orario_alunno['giustificata'] == ""))
		$add_spaces = true;
		
	$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE id_alunno = ".$orario_alunno['id_alunno'];
	$res_alunni = $db->execute($sel_alunni);
	$al = $res_alunni->fetch_assoc();
	
	// ricerca di note
	$sel_note = "SELECT * FROM rb_note_disciplinari WHERE alunno = ".$orario_alunno['id_alunno']." AND data = '".$orario_alunno['data']."' ORDER BY id_nota ASC";
	$res_note = $db->executeQuery($sel_note);
	$num_note = $res_note->num_rows;
	$tooltip = "";
	if($num_note == 1){
		$nt = $res_note->fetch_assoc();
		$tooltip = $nt['descrizione'];
	}
	else if($num_note > 1){
		while($nt = $res_note->fetch_assoc()){
			$tooltip .= $nt['descrizione']."|";
		}
		$tooltip = substr($tooltip, 0, -1);
	}
?>
	<tr style="border-bottom: 1px solid rgba(211, 222, 199, 0.6)">
		<td style="width: 30%; padding-left: 8px; font-weight: normal; "><?php print ucfirst($giorno_str)." ". format_date($orario_alunno['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
		<td style="width: 15%; text-align: center; font-weight: normal; " <?php if($assente) print("colspan='2'") ?>><?php if($assente) print "Assente"; else print $entrata ?></td>
<?php 
	if(!$assente){	
?>
		<td style="width: 15%; text-align: center; font-weight: normal;  "><?php print $uscita ?></td>
<?php 
	}
?>
		<td style="width: 40%; text-align: center;  font-weight: normal;">
			<span id="disc<?= $orario_alunno['id_alunno'] ?>"><?php if($entrata == "A" && ($orario_alunno['giustificata'] == 0 || $orario_alunno['giustificata'] == "")) print ("Assenza da giustificare"); ?><?php if($num_note > 0){ if($add_spaces) print ("&nbsp;&nbsp;|&nbsp;&nbsp;"); ?><a style='text-decoration: underline; color: #161414; font-weight: normal' href="#" title="<?= $tooltip ?>" onmouseover="show_note(this, <?= $num_note ?>)" onmouseout="hide_note(this, <?= $num_note ?>)"><?= $num_note ?> note disciplinari</a><?php } ?></span>
		</td>
	</tr>
<?php
}
?>
		<tr>
			<td colspan="4" style="height: 30px;"></td>
		</tr>
		<tr>
			<td colspan="4" style="margin: 30px auto 0 auto; text-align: center; padding-right: 10px; height: 35px; border-width: 1px 0 1px 0; border-style: solid; border-color: rgba(211, 222, 199, 0.6)">
				<?php if ($previous): ?>
				<a href="registro.php?month=<?php echo $previous ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 28px;">
					<?php echo $mesi[$previous - 1] ?>
				</a>
				<?php else: ?>
				<span style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 28px">&lt; &lt;</span>
				<?php endif; ?>
				<span style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 28px; margin-left: 28px"><?php echo $mesi[$month - 1] ?></span>
				<?php if ($next): ?>
				<a href="registro.php?month=<?php echo $next ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-left: 28px;">
					<?php echo $mesi[$next - 1] ?>
				</a>
				<?php else: ?>
				<span style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-left: 28px;">&gt; &gt;</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: right; height: 40px; padding-top: 30px">
				<a href="riepilogo_registro.php?q=0" style="padding-right: 8px; text-decoration: none; text-transform: uppercase">Riepilogo assenze e ritardi</a>|
				<a href="riepilogo_note.php?q=0" style="padding-left: 8px; text-decoration: none; text-transform: uppercase">Riepilogo note disciplinari</a>
			</td>
		</tr>
	</table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
