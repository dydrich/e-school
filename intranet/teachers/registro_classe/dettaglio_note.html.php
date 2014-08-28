<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Dettaglio note disciplinari</title>
<link rel="stylesheet" href="reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var win;
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var tempX = 0;
var tempY = 0;

var _show = function(e) {
	var hid = $('#tipinota');
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
    hid.css({top:  parseInt(tempY)+"px"});
    hid.css({left: parseInt(tempX)+"px"});
    hid.show();
    return true;
};

var note_manager = function(stid, nid){
	//win = new Window({className: "mac_os_x", url: "new_note.php?stid="+stid,  width:400, height:210, zIndex: 100, resizable: true, title: "Note didattica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	//win.showCenter(true);
	if (nid == 0) {
		$('#iframe').attr("src", "new_note.php?stid="+stid);
	}
	else {
		$('#iframe').attr("src", "new_note.php?id_nota="+nid+"&stid="+stid+"&action=update");
	}

	$('#nota').dialog({
		autoOpen: true,
		show: {
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
			duration: 300
		},
		modal: true,
		width: 450,
		title: 'Nuova nota',
		open: function(event, ui){

		}
	});
};

var dialogclose = function(){
	$('#nota').dialog("close");
};

$(function(){
	$('.note_link').click(function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		if (strs[3] == 0) {
			alert("Non hai i permessi per modificare la nota");
			return false;
		}
		note_manager(strs[1], strs[2]);
	});
});

</script>
</head>
<body <?php if(isset($msg) && $msg != "") echo("onload='_alert(\"$msg\")'") ?>>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<form>
<?php 
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="4"><?php echo $_SESSION['__current_year__']->to_string() ?>::classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?> - Note disciplinari di <?php print $alunno['cognome']." ".$alunno['nome'] ?> <span style="float: right; padding-right: 10px" ><!-- <a href="pdf_media_materia.php?stid=<?php print $student_id ?>&q=<?php print $q ?>">[ PDF ]</a> --></span></td>
</tr>
<tr style="height: 1.7em">
	<td colspan="4" style="text-align: right; padding-right: 30px"><a href="dettaglio_note.php?al=<?php echo $student_id ?>&q=<?php echo $q ?>&order=<?php if($order == "data") echo "tipo"; else echo "data" ?>" style="font-weight: normal">Ordina per <?php if($order == "data") print "tipo"; else print "data" ?></a> | <a href="#" onclick="_show(event)" style="font-weight: normal">Filtra per tipo nota</a></td>
</tr>
<tr> 
	<td style="width: 10%; text-align: center; "><span style="font-weight: bold; ">Data</span></td> 
	<td style="width: 25%; text-align: center; "><span style="font-weight: bold; ">Tipo nota</span></td>
	<td style="width: 20%; text-align: center; "><span style="font-weight: bold; ">Docente</span></td>  
	<td style="width: 45%; text-align: center; "><span style="font-weight: bold; ">Commento</span></td>   
</tr>
</thead>
<tbody id="tbody">
<?php
if($res_note->num_rows < 1){
?>
<tr id="no_notes_tr" >
	<td colspan="4" style="height: 50px; text-align: center; font-weight: bold">Nessuna annotazione presente</td>
</tr>	
<?php 	
}
$background = "";
$index = 1;
while($row = $res_note->fetch_assoc()){
	if($row['tipo'] == 14){
		// sospensione
		$background = "background-color: rgba(131, 2, 29, 0.2)";
	}
	else {
		$background = "";
	}
?>
<tr id="row<?php echo $row['id_nota'] ?>" style="<?php echo $background ?>">
	<td style="width: 10%; text-align: center; "><a id="datlink_<?php echo $student_id ?>_<?php echo $row['id_nota'] ?>_<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal; "><?php echo format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td>
	<td style="width: 25%; text-align: center; "><a id="typlink_<?php echo $student_id ?>_<?php echo $row['id_nota'] ?>_<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal; "><?php echo $row['tipo_nota'] ?></a></td>
	<td style="width: 20%; text-align: center; "><a id="doclink_<?php echo $student_id ?>_<?php echo $row['id_nota'] ?>_<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal; "><?php if($row['id_tiponota'] > 2) echo "--"; else echo $row['cognome']." ".$row['nome'] ?></a></td>
	<td style="width: 45%; text-align: center; "><a id="comlink_<?php echo $student_id ?>_<?php echo $row['id_nota'] ?>_<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal; "><?php echo $row['descrizione'] ?></a></td>
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
	<td colspan="4" style="text-align: right; "
		><a href="#" onclick="note_manager(<?php echo $alunno['id_alunno'] ?>, 0)" class="standard_link" style="margin-right: 30px">Nuova nota</a>
	</td>
</tr>
</tfoot>
</table>
</form>
</div>
<?php include "../footer.php" ?>
<!-- tipi nota -->
    <div id="tipinota" style="display: none; ">
    	<a style="font-weight: normal" href="dettaglio_note.php?al=<?php echo $student_id ?>&q=<?php echo $q ?>&order=data">Tutte le note</a><br />
    <?php 
    while($t = $res_tipi->fetch_assoc()){
    ?>
    	<a style="font-weight: normal" href="dettaglio_note.php?al=<?php echo $student_id ?>&q=<?php echo $q ?>&order=data&tipo=<?php echo $t['id_tiponota'] ?>"><?php echo $t['descrizione'] ?></a><br />
    <?php } ?>
    	<br /><a style="font-weight: normal" href="#" onclick="$('#tipinota').hide()">Chiudi</a>
    </div>
<!-- tipi nota -->
<div id="nota" style="display: none">
	<iframe id="iframe" src="new_note.php" style="width: 400px; height: 250px"></iframe>
</div>
</body>
</html>
