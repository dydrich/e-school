<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="../../../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
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

function new_note(stid){
	win = new Window({className: "mac_os_x", url: "new_note.php?stid="+stid,  width:400, height:250, zIndex: 100, resizable: true, title: "Note didattica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});	
	win.showCenter(true);
}

function update_note(id_note){
	win = new Window({className: "mac_os_x",  url: "new_note.php?id_nota="+id_note+"&action=update", width:400, height:220, zIndex: 100, resizable: true, title: "Dettaglio nota", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	win.showCenter(true);		
}

document.observe("dom:loaded", function(){
	$('tipinota').observe("mouseleave", function(event){
		this.hide();
	});
	
});

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<?php 
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="3" style="text-align: center; font-weight: bold; text-transform: uppercase">Elenco note didattiche <span style="float: right; padding-right: 10px" ><!-- <a href="pdf_media_materia.php?stid=<?php print $student_id ?>&q=<?php print $q ?>">[ PDF ]</a> --></span></td>
</tr>
<tr class="head_tr_no_bg">
	<td colspan="2" style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $alunno['cognome']." ".$alunno['nome'] ?>: <?php print $desc_materia ?></span></td> 
	<td style="text-align: right; padding-right: 30px "><a href="student_notes.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>&order=<?php if($order == "data") print "tipo"; else print "data" ?>" style="font-weight: normal; text-decoration: none; text-transform: uppercase; margin-right: 10px">Ordina per <?php if($order == "data") print "tipo"; else print "data" ?></a> | <a href="#" onclick="_show(event)" style="margin-left: 10px; font-weight: normal; text-decoration: none; text-transform: uppercase">Filtra per tipo nota</a></td>
</tr>
<tr class="title_tr"> 
	<td style="width: 20%; text-align: center"><span style="font-weight: bold; ">Data</span></td> 
	<td style="width: 30%; text-align: center"><span style="font-weight: bold; ">Tipo nota</span></td>  
	<td style="width: 50%; text-align: center"><span style="font-weight: bold; ">Commento</span></td>   
</tr>
</thead>
<tbody>
<?php
if($res_note->num_rows < 1){
?>
<tr>
	<td colspan="3" style="height: 50px; text-align: center; font-weight: bold">Nessuna annotazione presente</td>
</tr>	
<?php 	
}
$background = "";
$index = 1;
$array_voti = array();
while($row = $res_note->fetch_assoc()){
	if($index % 2)
		$background = "background-color: #e8eaec";
	else
		$background = "";
?>
<tr> 
	<td style="width: 20%; text-align: center"><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; color: #303030"><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td> 
	<td style="width: 30%; text-align: center"><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; color: #303030"><?php print $row['tipo_nota'] ?></a></td> 
	<td style="width: 50%; text-align: center"><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; color: #303030"><?php print $row['note'] ?></a></td>   
</tr>
<?php 
	$index++;
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="3" style="">&nbsp;</td>
</tr>
<tr class="nav_tr"> 
	<td colspan="2" style="text-align: left"></td> 
	<td style="text-align: right">
		<a href="student.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>" style="margin-right: 10px; text-decoration: none; text-transform: uppercase">Torna ai voti</a>|
		<a href="#" onclick="new_note(<?php print $alunno['id_alunno'] ?>)" style="margin-right: 30px; margin-left: 10px; text-decoration: none; text-transform: uppercase">Nuova nota</a>
	</td> 
</tr>
</tfoot>
</table>
</div>
<?php include "../footer.php" ?>
<!-- tipi nota -->
    <div id="tipinota" style="position: absolute; width: 200px; height: 130px; display: none">
    	<a style="font-weight: normal" href="student_notes.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>&order=data">Tutte le note</a><br />
    <?php 
    while($t = $res_tipi->fetch_assoc()){
    ?>
    	<a style="font-weight: normal" href="student_notes.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>&order=data&tipo=<?= $t['id_tiponota'] ?>"><?= $t['descrizione'] ?></a><br />
    <?php } ?>
    </div>
<!-- tipi nota -->
</body>
</html>