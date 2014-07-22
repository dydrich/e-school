<?php

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['order'])){
	$order = $_REQUEST['order'];
}
else{
	$order = "data";
}

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

if(isset($_REQUEST['tipo'])){
	$q_type = "AND tipo = ".$_REQUEST['tipo'];
}
else{
	$q_type = "";
}

switch($q){
	case 0:
		$int_time = "AND data <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data <= '".$fine_q."'";
		$label = "[primo quadrimestre]";
		break;
	case 2:
		$int_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = "[secondo quadrimestre]";
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $student_id";
$sel_materia = "SELECT materia FROM rb_materie WHERE id_materia = ".$_REQUEST['materia'];
$sel_tipi = "SELECT * FROM rb_tipi_note_didattiche ORDER BY id_tiponota";
$sel_note = "SELECT rb_note_didattiche.*, rb_tipi_note_didattiche.descrizione AS tipo_nota FROM rb_note_didattiche, rb_tipi_note_didattiche WHERE id_tiponota = tipo AND alunno = $student_id AND materia = ".$_REQUEST['materia']." AND anno = {$_SESSION['__current_year__']->get_ID()} $int_time $q_type ORDER BY $order DESC";
//print $sel_voti;
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_materia = $db->executeQuery($sel_materia);
	$res_note = $db->executeQuery($sel_note);
	$res_tipi = $db->executeQuery($sel_tipi);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

$mt = $res_materia->fetch_assoc();
$desc_materia = $mt['materia'];

$link = "elenco_note_didattiche.php?materia=".$_REQUEST['materia'];

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var tempX = 0;
var tempY = 0;

function _show(e) {
	var hid = document.getElementById("tipinota");
    //alert(hid.style.top);
    if (IE) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    hid.style.top = parseInt(tempY)+"px";
    //alert(hid.style.top);
    hid.style.left = parseInt(tempX)+"px";
    hid.style.display = "inline";
    return true;
}
</script>
<style type="text/css">
TD{height: 20px; border-color: white}
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
<?php 
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Note disciplinari di <?php echo $student ?>: <?php print $desc_materia ?> <?php echo $label ?>
	</div>
	<div style="width: 95%; margin: 0 auto 20px auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 20%; float: left; position: relative; top: 30%">Data</div>
		<div style="width: 30%; float: left; position: relative; top: 30%">Tipo nota</div>
		<div style="width: 50%; float: left; position: relative; top: 30%">Commento</div>
	</div>
	<table style="width: 95%; border-collapse: collapse; margin: auto">
<?php
if($res_note->num_rows < 1){
?>
		<tr>
	    	<td colspan="3" style="height: 150px; font-weight: bold; text-transform: uppercase; text-align: center">Nessuna nota presente</td> 
	    </tr>		
<?php 	
}
$background = "";
$index = 1;
$array_voti = array();
while($row = $res_note->fetch_assoc()){
	if($index % 2) {
		$background = "background-color: #e8eaec";
	}
	else {
		$background = "";
	}
?>
		<tr style="border-bottom: 1px solid rgb(211, 222, 199)">  
			<td style="width: 20%; text-align: center; "><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td> 
			<td style="width: 30%; text-align: center; "><?php print $row['tipo_nota'] ?></td> 
			<td style="width: 50%; text-align: center; "><?php print $row['note'] ?></td>   
		</tr>
<?php 
	$index++;
}
?>
		<tr>
	    	<td colspan="3" style="height: 25px"></td> 
	    </tr>
		<tr>
			<td colspan="2" style="text-align: right;"></td>
			<td style="text-align: right; width: 50%">
			<div style="width: 100%; height: 20px; border: 1px solid rgb(211, 222, 199); border-radius: 8px; background-color: rgba(211, 222, 199, 0.4)">
				<span id="ingresso" style="font-weight: bold; "></span>
				<a href="elenco_note_didattiche.php?materia=<?php echo $_REQUEST['materia'] ?>&q=<?php echo $q ?>&order=<?php if($order == "data") print "tipo"; else print "data" ?>" style="font-weight: normal; text-decoration: none; text-transform: uppercase; position: relative; top: 15%">Ordina per <?php if($order == "data") print "tipo"; else print "data" ?></a> | 
				<a href="#" onclick="_show(event)" style="font-weight: normal; text-decoration: none; text-transform: uppercase; padding-right: 20px; position: relative; top: 15%">Filtra per tipo nota</a>
			</div>
			</td>
		</tr>
	</table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<!-- tipi nota -->
    <div id="tipinota" style="position: absolute; width: 200px; height: 160px; display: none; ">
    	<a style="font-weight: normal; font-size: 11px" href="<?= $link ?>&q=<?= $q ?>&order=data">Tutte le note</a><br />
    <?php 
    while($t = $res_tipi->fetch_assoc()){
    ?>
    	<a style="font-weight: normal; font-size: 11px" href="<?= $link ?>&q=<?= $q ?>&order=data&tipo=<?= $t['id_tiponota'] ?>"><?= $t['descrizione'] ?></a><br />
    <?php } ?>
    	<br /><a style="font-weight: normal; font-size: 11px" href="#" onclick="$('tipinota').style.display = 'none'">Chiudi</a>
    </div>
<!-- tipi nota -->
</body>
</html>
