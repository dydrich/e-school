<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var win;
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
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
	<div class="group_head">
		<?php print $_SESSION['__current_year__']->to_string() ?> - Note disciplinari di <?php print $alunno['cognome']." ".$alunno['nome'] ?> <span style="float: right; padding-right: 10px" ></span>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 10%; float: left; position: relative; top: 30%">Data</div>
		<div style="width: 25%; float: left; position: relative; top: 30%">Tipo</div>
		<div style="width: 20%; float: left; position: relative; top: 30%">Docente</div>
		<div style="width: 45%; float: left; position: relative; top: 30%">Commento</div>
	</div>
	<table style="width: 95%; border-collapse: collapse; margin: auto">
	 
<?php
if($res_note->num_rows < 1){
?>
	<tr>
    	<td colspan="4" style="height: 150px; font-weight: bold; text-transform: uppercase; text-align: center">Nessuna nota presente</td> 
    </tr>	
<?php 	
}
else{
	$background = "";
	$index = 1;
	while($row = $res_note->fetch_assoc()){
		if($index % 2)
			$background = "background-color: #e8eaec";
		else
			$background = "";
?>
	<tr class="manager_row_small">
		<td style="width: 10%; text-align: center; "><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td> 
		<td style="width: 25%; text-align: center; "><?php print $row['tipo_nota'] ?></td> 
		<td style="width: 20%; text-align: center; "><?php if($row['id_tiponota'] > 1) print "--"; else print $row['cognome']." ".$row['nome'] ?></td>
		<td style="width: 45%; text-align: center; "><?php print $row['descrizione'] ?></td>   
	</tr>
<?php 
		$index++;
	}
?>
	<tr>
    	<td colspan="4" style="height: 25px"></td> 
    </tr>
	<tr>
		<td colspan="3" style="text-align: right;"></td>
		<td style="text-align: right; width: 45%">
		<div style="width: 100%; height: 20px; border: 1px solid rgb(211, 222, 199); border-radius: 8px; background-color: rgba(211, 222, 199, 0.4)">
			<span id="ingresso" style="font-weight: bold; "></span>
			<a href="riepilogo_note.php?q=<?= $q ?>&order=<?php if($order == "data") print "tipo"; else print "data" ?>" style="font-weight: normal; text-decoration: none; text-transform: uppercase; position: relative; top: 15%">Ordina per <?php if($order == "data") print "tipo"; else print "data" ?></a> | 
			<a href="#" onclick="_show(event)" style="font-weight: normal; text-decoration: none; text-transform: uppercase; padding-right: 20px; position: relative; top: 15%">Filtra per tipo nota</a>
		</div>
		</td>
	</tr>
<?php 
}
?>
	<tr>
		<td colspan="4" style="height: 30px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" style="tmargin: 30px auto 0 auto; text-align: center; padding-right: 10px; height: 35px; border-width: 1px 0 1px 0; border-style: solid; border-color: rgba(30, 67, 137, .3);">
			<a href="riepilogo_note.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="riepilogo_note.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />2 Quadrimestre
			</a>
			<a href="riepilogo_note.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />Totale
			</a>
		</td>
	</tr>
	</table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<!-- tipi nota -->
    <div id="tipinota" style="position: absolute; width: 200px; height: 140px; display: none; ">
    	<a style="font-size: 11px; font-weight: normal" href="riepilogo_note.php?q=<?= $q ?>&order=data">Tutte le note</a><br />
    <?php 
    while($t = $res_tipi->fetch_assoc()){
    ?>
    	<a style="font-size: 11px; font-weight: normal" href="riepilogo_note.php?q=<?= $q ?>&order=data&tipo=<?= $t['id_tiponota'] ?>"><?= $t['descrizione'] ?></a><br />
    <?php } ?>
    	<br /><a style="font-size: 11px; font-weight: normal" href="#" onclick="$('tipinota').style.display = 'none'">Chiudi</a>
    </div>
<!-- tipi nota -->
</body>
</html>
