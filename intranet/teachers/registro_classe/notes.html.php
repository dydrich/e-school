<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Note disciplinari di classe </title>
<link rel="stylesheet" href="reg_classe.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
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
    tempX -= 300;
    hid.style.top = parseInt(tempY)+"px";
    //alert(hid.style.top);
    hid.style.left = parseInt(tempX)+"px";
    hid.style.display = "inline";
    return true;
}

function new_note(){
	win = new Window({className: "mac_os_x", url: "new_note.php",  width:400, height:210, zIndex: 100, resizable: true, title: "Note disciplinari", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});	
	win.showCenter(true);
}

function update_note(id_note){
	win = new Window({className: "mac_os_x",  url: "new_note.php?id_nota="+id_note+"&action=update", width:400, height:210, zIndex: 100, resizable: true, title: "Dettaglio nota", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	win.showCenter(true);		
}

</script>
</head>
<body <?php if($msg != "") print("onload='alert(\"$msg\")'") ?>>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<form>
<?php 
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="4" style="text-align: center; font-weight: bold"><?php echo $_SESSION['__classe__']->to_string() ?> - Note disciplinari di classe <span style="float: right; padding-right: 10px" ><!-- <a href="pdf_media_materia.php?stid=<?php print $student_id ?>&q=<?php print $q ?>">[ PDF ]</a> --></span></td>
</tr>
<tr class="head_tr_no_bg">
	<td colspan="4" style="text-align: right; padding-right: 10px"><a href="notes.php?q=<?= $q ?>&order=<?php if($order == "data") print "tipo"; else print "data" ?>" style="font-weight: normal">Ordina per <?php if($order == "data") print "tipo"; else print "data" ?></a> | <a href="#" onclick="_show(event)" style="font-weight: normal">Filtra per tipo nota</a></td>
</tr>
<tr class="title_tr"> 
	<td style="width: 10%; text-align: center; "><span style="font-weight: bold; ">Data</span></td> 
	<td style="width: 25%; text-align: center; "><span style="font-weight: bold; ">Tipo nota</span></td>
	<td style="width: 20%; text-align: center; "><span style="font-weight: bold; ">Docente</span></td>  
	<td style="width: 45%; text-align: center; "><span style="font-weight: bold; ">Commento</span></td>   
</tr>
</thead>
<tbody>
<?php
if($res_note->num_rows < 1){
?>
<tr>
	<td colspan="4" style="height: 50px; text-align: center; font-weight: bold">Nessuna annotazione presente</td>
</tr>	
<?php 	
}
$background = "";
$index = 1;
while($row = $res_note->fetch_assoc()){
	
?>
<tr> 
	<td style="width: 10%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; color: #303030"><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td> 
	<td style="width: 25%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; color: #303030"><?php print $row['tipo_nota'] ?></a></td> 
	<td style="width: 20%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; color: #303030"><?php if($row['id_tiponota'] > 1) print "--"; else print $row['cognome']." ".$row['nome'] ?></a></td>
	<td style="width: 45%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; color: #303030"><?php print $row['descrizione'] ?></a></td>   
</tr>
<?php 
	$index++;
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>	
<tr class="nav_tr"> 
	<td colspan="4" style="text-align: right; "><a href="#" onclick="new_note()" style="margin-right: 30px">Nuova nota</a></td> 
</tr>
</tfoot>
</table>
</form>
<p>
</p> 
</div>
<?php include "../footer.php" ?>
<!-- tipi nota -->
    <div id="tipinota" style="display: none;">
    	<a style="font-weight: normal" href="notes.php?q=<?= $q ?>&order=data">Tutte le note</a><br />
    <?php 
    while($t = $res_tipi->fetch_assoc()){
    ?>
    	<a style="font-weight: normal" href="notes.php?q=<?= $q ?>&order=data&tipo=<?= $t['id_tiponota'] ?>"><?= $t['descrizione'] ?></a><br />
    <?php } ?>
    	<br /><a style="font-weight: normal" href="#" onclick="$('tipinota').hide()">Chiudi</a>
    </div>
<!-- tipi nota -->
</body>
</html>