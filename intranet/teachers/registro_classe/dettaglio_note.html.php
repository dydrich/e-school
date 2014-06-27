<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Dettaglio note disciplinari</title>
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="reg_classe.css" type="text/css" media="screen,projection" />
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
	var hid = $('tipinota');
    //alert(hid.style.top);
    if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    hid.setStyle({top:  parseInt(tempY)+"px"});
    hid.setStyle({left: parseInt(tempX)+"px"});
    hid.show();
    return true;
}

function new_note(stid){
	win = new Window({className: "mac_os_x", url: "new_note.php?stid="+stid,  width:400, height:210, zIndex: 100, resizable: true, title: "Note didattica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});	
	win.showCenter(true);
}

function update_note(id_note){
	win = new Window({className: "mac_os_x",  url: "new_note.php?id_nota="+id_note+"&action=update", width:400, height:210, zIndex: 100, resizable: true, title: "Dettaglio nota", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	win.showCenter(true);		
}

</script>
</head>
<body <?php if($msg != "") print("onload='_alert(\"$msg\")'") ?>>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<form>
<?php 
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<tr class="head_tr">
	<td colspan="4"><?php print $_SESSION['__current_year__']->to_string() ?>::classe <?php print $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?> - Note disciplinari di <?php print $alunno['cognome']." ".$alunno['nome'] ?> <span style="float: right; padding-right: 10px" ><!-- <a href="pdf_media_materia.php?stid=<?php print $student_id ?>&q=<?php print $q ?>">[ PDF ]</a> --></span></td>
</tr>
<tr style="height: 1.7em">
	<td colspan="4" style="text-align: right; padding-right: 30px"><a href="dettaglio_note.php?al=<?= $student_id ?>&q=<?= $q ?>&order=<?php if($order == "data") print "tipo"; else print "data" ?>" style="font-weight: normal">Ordina per <?php if($order == "data") print "tipo"; else print "data" ?></a> | <a href="#" onclick="_show(event)" style="font-weight: normal">Filtra per tipo nota</a></td>
</tr>
<tr> 
	<td style="width: 10%; text-align: center; "><span style="font-weight: bold; ">Data</span></td> 
	<td style="width: 25%; text-align: center; "><span style="font-weight: bold; ">Tipo nota</span></td>
	<td style="width: 20%; text-align: center; "><span style="font-weight: bold; ">Docente</span></td>  
	<td style="width: 45%; text-align: center; "><span style="font-weight: bold; ">Commento</span></td>   
</tr> 
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
	if($row['tipo'] == 5){
		// sospensione
		$background = "background-color: rgba(131, 2, 29, 0.2)";
	}
	else {
		$background = "";
	}
?>
<tr style="<?php echo $background ?>"> 
	<td style="width: 10%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; "><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td> 
	<td style="width: 25%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; "><?php print $row['tipo_nota'] ?></a></td> 
	<td style="width: 20%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; "><?php if($row['id_tiponota'] > 1) print "--"; else print $row['cognome']." ".$row['nome'] ?></a></td>
	<td style="width: 45%; text-align: center; "><a href="#" onclick="update_note(<?= $row['id_nota'] ?>)" style="font-weight: normal; "><?php print $row['descrizione'] ?></a></td>   
</tr>
<?php 
	$index++;
}
?>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>	
<tr class="nav_tr">
	<td colspan="4" style="text-align: right; "><a href="#" onclick="new_note(<?php print $alunno['id_alunno'] ?>)" style="margin-right: 30px">Nuova nota</a></td> 
</tr>
</table>
</form>
</div>
<?php include "../footer.php" ?>
<!-- tipi nota -->
    <div id="tipinota" style="display: none; ">
    	<a style="font-weight: normal" href="dettaglio_note.php?al=<?= $student_id ?>&q=<?= $q ?>&order=data">Tutte le note</a><br />
    <?php 
    while($t = $res_tipi->fetch_assoc()){
    ?>
    	<a style="font-weight: normal" href="dettaglio_note.php?al=<?= $student_id ?>&q=<?= $q ?>&order=data&tipo=<?= $t['id_tiponota'] ?>"><?= $t['descrizione'] ?></a><br />
    <?php } ?>
    	<br /><a style="font-weight: normal" href="#" onclick="$('tipinota').style.display = 'none'">Chiudi</a>
    </div>
<!-- tipi nota -->
</body>
</html>