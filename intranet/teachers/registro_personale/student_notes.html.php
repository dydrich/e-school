<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../registro_classe/reg_print.css" type="text/css" media="print" />
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

var action = "";
var note_id = 0;

var _show = function(e) {
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
};

var new_note = function(){
	$('#titolo_nota').text("Nuova nota");
	$('#action').val("new");
	$('#id_nota').val(0);
	$('#ndate').val('<?php echo date("d/m/Y") ?>');
	$('#pop_note').dialog({
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

var update_note = function(id_note, can_modify_annotation){
	if (can_modify_annotation == 0) {
		alert("Non hai i permessi per modificare questa nota");
		return false;
	}
	$('#titolo_nota').text("Modifica nota");
	$('#del_button').show();
	$.ajax({
		type: "POST",
		url: "note_manager.php",
		data:  {
			action: 'get',
			id_nota: id_note,
			q: <?php echo $q ?>
		},
		dataType: 'json',
		error: function(data, status, errore) {
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				$('#ndate').val(json.note.data);
				$('#ntype').val(json.note.tipo);
				$('#desc').val(json.note.note);
			}
		}
	});
	$('#action').val("update");
	$('#id_nota').val(id_note);
	$('#pop_note').dialog({
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
		title: 'Nuovo voto',
		open: function(event, ui){

		}
	});
};

var register_note = function(){
	if ($('#ndate').val() == "") {
		alert("Data obbligatoria");
		return false;
	}
	var url = "note_manager.php";
	ndate = $('#ndate').val();
	ntype = $('#ntype option:selected').text();
	desc = $('#desc').val()
	note_id = $('#id_nota').val();
	$.ajax({
		type: "POST",
		url: url,
		data:  $('#testform').serialize(true),
		dataType: 'json',
		error: function(data, status, errore) {
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				if ($('#action').val() == 'new'){
					note_id = json.id;
					var tr = document.createElement("tr");
					tr.setAttribute("id", "tr_"+json.id);
					var td1 = document.createElement("td");
					td1.setAttribute("style", "width: 20%; text-align: center");
					var _a = document.createElement("a");
					_a.setAttribute("href", "#");
					_a.setAttribute("id", "dn_"+note_id);
					td1.appendChild(_a);
					tr.appendChild(td1);

					var td2 = document.createElement("td");
					td2.setAttribute("style", "width: 30%; text-align: center");
					var _a = document.createElement("a");
					_a.setAttribute("href", "#");
					_a.setAttribute("id", "tn_"+note_id);
					td2.appendChild(_a);
					tr.appendChild(td2);

					var td3 = document.createElement("td");
					td3.setAttribute("style", "width: 50%; text-align: center");
					var _a = document.createElement("a");
					_a.setAttribute("href", "#");
					_a.setAttribute("id", "nn_"+note_id);
					td3.appendChild(_a);
					tr.appendChild(td3);

					$('#tbody').prepend(tr);

					$('#dn_'+note_id).click(function(event){
						//alert(this.id);
						update_note(note_id);
					});
					$('#tn_'+note_id).click(function(event){
						//alert(this.id);
						update_note(note_id);
					});
					$('#nn_'+note_id).click(function(event){
						//alert(this.id);
						update_note(note_id);
					});

					$('#dn_'+note_id).css({fontWeight: "normal", color: "#303030"});
					$('#tn_'+note_id).css({fontWeight: "normal", color: "#303030"});
					$('#nn_'+note_id).css({fontWeight: "normal", color: "#303030"});

					$('#dn_'+note_id).addClass("note_link");
					$('#tn_'+note_id).addClass("note_link");
					$('#nn_'+note_id).addClass("note_link");

				}

				$('#dn_'+note_id).text(ndate);
				$('#tn_'+note_id).text(ntype);
				$('#nn_'+note_id).text(desc);

				$('#pop_note').dialog("close");
			}
		}
	});
};

var del_note = function(){
	note_id = $('#id_nota').val();
	if(!confirm("Sei sicuro di voler cancellare questa nota?")) {
		$('#pop_note').dialog("close");
		return false;
	}
	var url = "note_manager.php";
	$.ajax({
		type: "POST",
		url: "note_manager.php",
		data:  {action: "delete", id_nota: note_id},
		dataType: 'json',
		error: function(data, status, errore) {
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				$('#tr_'+note_id).hide();
			}
			$('#pop_note').dialog("close");
		}
	});
};

$(function(){
	$('#tipinota').mouseleave(function(event){
		$('#tipinota').hide();
	});
	$('.note_link').click(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		update_note(strs[1], strs[2]);
	});
	$('#ndate').datepicker({
		dateFormat: "dd/mm/yy",
		altFormat: "dd/mm/yy"
	});
	$('#del_button').click(function(event){
		event.preventDefault();
		del_note();
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
<tbody id="tbody">
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
	$can_modify_annotation = 1;
	if ($_SESSION['__user__']->getUid() != $row['docente']) {
		$can_modify_annotation = 0;
	}
	if($index % 2)
		$background = "background-color: #e8eaec";
	else
		$background = "";
?>
<tr id="tr_<?php echo $row['id_nota'] ?>">
	<td style="width: 20%; text-align: center"><a href="#" id="dn_<?php echo $row['id_nota'] ?>_<?php echo $can_modify_annotation ?>" class="note_link" style="font-weight: normal; color: #303030"><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td>
	<td style="width: 30%; text-align: center"><a href="#" id="tn_<?php echo $row['id_nota'] ?>_<?php echo $can_modify_annotation ?>" class="note_link" style="font-weight: normal; color: #303030"><?php print $row['tipo_nota'] ?></a></td>
	<td style="width: 50%; text-align: center"><a href="#" id="nn_<?php echo $row['id_nota'] ?>_<?php echo $can_modify_annotation ?>" class="note_link" style="font-weight: normal; color: #303030"><?php print $row['note'] ?></a></td>
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
<!-- popup nota -->
<div id="pop_note" style="display: none">
	<p class="popup_header" id='titolo_nota'>Note didattiche</p>
	<form id='testform' method='post' onsubmit="_submit()">
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
			<tr>
				<td style="width: 25%; font-weight: bold">Tipo nota *</td>
				<td style="width: 75%; " colspan="3">
					<select id="ntype" name="ntype" style="font-size: 11px; border: 1px solid gray; width: 100%">
						<?php
						$res_tipi->data_seek(0);
						while($t = $res_tipi->fetch_assoc()){
							?>
							<option value="<?php echo $t['id_tiponota'] ?>"><?php echo utf8_decode($t['descrizione']) ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 25%; font-weight: bold">Data *</td>
				<td style="width: 75%; font-weight: normal" colspan="3">
					<input type="hidden" name="action" id="action" value="" />
					<input type="hidden" name="id_nota" id="id_nota" value="" />
					<input type="hidden" name="stid" id="stid" value="<?php echo $student_id ?>" />
					<input type="text" style="font-size: 11px; border: 1px solid gray; width: 99%" id="ndate" name="ndate" readonly="readonly" value="" />
				</td>
			</tr>
			<tr>
				<td style="width: 25%; font-weight: bold">Note </td>
				<td style="width: 75%; " colspan="3">
					<textarea style="width: 100%; height: 40px; font-size: 11px; border: 1px solid gray" id="desc" name="desc"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top: 20px; text-align: right;">
					<input type="button" id="del_button" value="Elimina" style="width: 70px; padding: 2px; display: none" />
					<input type="button" id="manage_link" onclick="register_note()" value="Registra" style="width: 70px; padding: 2px" />
				</td>
			</tr>
			<tr>
				<td colspan="4" style="height: 10px"></td>
			</tr>
		</table>
	</form>
</div>
</body>
</html>
