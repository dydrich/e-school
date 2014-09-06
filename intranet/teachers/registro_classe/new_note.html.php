<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Nota disciplinare</title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var note_manager = function(){
	var url = "note_manager.php";
	//alert(url);
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
				if ($('#action').val() == "insert") {
					var tr = document.createElement("tr");
					tr.setAttribute("id", "row"+json.id);
					if ($('#type').val() == 14) {
						tr.setAttribute("style", "background-color: rgba(131, 2, 29, 0.2)");
					}
					var td = document.createElement("td");
					td.setAttribute("style", "width: 10%; text-align: center");
					_a = document.createElement("a");
					if (json.class_note == 0) {
						_a.setAttribute("id", "datlink_<?php echo $stid ?>_"+json.id);
						_a.setAttribute("onclick", "note_manager(<?php echo $stid ?>, "+json.id+", 1)");
					}
					else {
						_a.setAttribute("id", "datlink_"+json.id);
						_a.setAttribute("onclick", "note_manager("+json.id+", 1)");
					}
					_a.setAttribute("href", "#");
					_a.setAttribute("class", "note_link");
					_a.setAttribute("style", "font-weight: normal");
					_a.appendChild(document.createTextNode($('#_date').val()));

					td.appendChild(_a);
					tr.appendChild(td);

					td = document.createElement("td");
					td.setAttribute("style", "width: 25%; text-align: center");
					_a = document.createElement("a");
					_a.setAttribute("href", "#");
					_a.setAttribute("class", "note_link");
					_a.setAttribute("style", "font-weight: normal");
					_a.appendChild(document.createTextNode($('#type option:selected').text()));
					if (json.class_note == 0) {
						_a.setAttribute("id", "typlink_<?php echo $stid ?>_"+json.id);
						_a.setAttribute("onclick", "note_manager(<?php echo $stid ?>, "+json.id+", 1)");
					}
					else {
						_a.setAttribute("id", "typlink"+json.id);
						_a.setAttribute("onclick", "note_manager("+json.id+", 1)");
					}
					td.appendChild(_a);
					tr.appendChild(td);

					td = document.createElement("td");
					td.setAttribute("style", "width: 20%; text-align: center");
					_a = document.createElement("a");
					_a.setAttribute("href", "#");
					_a.setAttribute("class", "note_link");
					_a.setAttribute("style", "font-weight: normal");
					if ($('#type').val() < 10) {
						_a.appendChild(document.createTextNode("<?php echo $_SESSION['__user__']->getFullName(2) ?>"));
					}
					else {
						_a.appendChild(document.createTextNode("--"));
					}
					if (json.class_note == 0) {
						_a.setAttribute("id", "doclink_<?php echo $stid ?>_"+json.id);
						_a.setAttribute("onclick", "note_manager(<?php echo $stid ?>, "+json.id+", 1)");
					}
					else {
						_a.setAttribute("id", "doclink"+json.id);
						_a.setAttribute("onclick", "note_manager("+json.id+", 1)");
					}
					td.appendChild(_a);
					tr.appendChild(td);

					td = document.createElement("td");
					td.setAttribute("style", "width: 40%; text-align: center");
					_a = document.createElement("a");
					_a.setAttribute("href", "#");
					_a.setAttribute("class", "note_link");
					_a.setAttribute("style", "font-weight: normal");
					_a.appendChild(document.createTextNode($('#desc').val()));
					if (json.class_note == 0) {
						_a.setAttribute("id", "comlink_<?php echo $stid ?>_"+json.id);
						_a.setAttribute("onclick", "note_manager(<?php echo $stid ?>, "+json.id+", 1)");
					}
					else {
						_a.setAttribute("id", "comlink"+json.id);
						_a.setAttribute("onclick", "note_manager("+json.id+", 1)");
					}
					td.appendChild(_a);
					tr.appendChild(td);

					parent.$('#tbody').prepend(tr);
					parent.$('#no_notes_tr').hide();
				}
				else {
					parent.$('#datlink_<?php echo $stid ?>_'+json.id).text($('#_date').val());
					parent.$('#typlink_<?php echo $stid ?>_'+json.id).text($('#type option:selected').text());
					if ($('#type').val() > 10) {
						parent.$('#doclink_<?php echo $stid ?>_'+json.id).text("--");
					}
					parent.$('#comlink_<?php echo $stid ?>_'+json.id).text($('#desc').val());
				}
				parent.dialogclose();
			}
		}
	});
};

var del_note = function(id_nota){
	if(!confirm("Sei sicuro di voler cancellare questa nota?"))
		return false;
	var url = "note_manager.php";
	//alert(url);
	$.ajax({
		type: "POST",
		url: url,
		data:  {action: "delete", id_nota: id_nota},
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
			else{
				alert("Nota eliminata --"+json.count);
				parent.$('#row'+json.id).hide();
				if (json.count == 0) {
					var tr = document.createElement("tr");
					tr.setAttribute("id", "no_notes_tr");
					var td = document.createElement("td");
					td.setAttribute("colspan", "4");
					td.setAttribute("style", "height: 50px; text-align: center; font-weight: bold");
					td.appendChild(document.createTextNode("Nessuna nota presente"));
					tr.appendChild(td);
					parent.$('#tbody').prepend(tr);
				}
			}
			parent.dialogclose();
		}
	});
};

var _submit = function(){
	msg = "Ci sono degli errori nella compilazione del modulo.";
	go = true;
	index = 1;
	if($('#desc').val() == ""){
		msg += "\n"+index+". Descrizione della nota non presente";
		$('#desc_lab').css({color: "red"});
		$('#desc_lab').addClass("attention");
		go = false;
	}
	if(!go){
		alert(msg);
		return false;
	}
	note_manager();
};

$(function(){
	$('#_date').datepicker({
		dateFormat: "dd/mm/yy",
		altFormat: "dd/mm/yy"
	});
});

</script>
</head>
<body class="popup_body">
<div id="popup_main">
	<p class="popup_header" id='titolo'>Note disciplinari</p>
	<form id='testform' action='manage_test.php' method='post' class="popup_form" onsubmit="_submit()">
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
		<tr>
			<td style="width: 25%; font-weight: bold">Tipo nota *</td>
			<td style="width: 75%; " colspan="3">
				<select id="type" name="type" style="font-size: 11px; width: 100%">
				<?php 
				while($t = $res_types->fetch_assoc()){
				?>
					<option <?php if(isset($nota) && $nota['tipo'] == $t['id_tiponota']) print("selected='selected'") ?> value="<?php echo $t['id_tiponota'] ?>"><?= utf8_decode($t['descrizione']) ?></option>
				<?php } ?>
				</select>
			</td>
			</tr>
			<tr>
			<td style="width: 25%; font-weight: bold" id="desc_lab">Descrizione *</td>
			<td style="width: 75%; " colspan="3">
				<textarea style="width: 100%; height: 40px; font-size: 11px; border: 1px solid gray" id="desc" name="desc"><?php if(isset($nota)) print(utf8_decode($nota['descrizione'])) ?></textarea>
			</td>
			</tr>
			<tr>
			<td style="width: 25%; font-weight: bold">Data *</td>
			<td style="width: 75%; font-weight: normal" colspan="3">
				<input type="hidden" name="action" id="action" value="<?php if(isset($nota)) print "update"; else print "insert" ?>" />
				<input type="hidden" name="stid" id="stid" value="<?php echo $stid ?>" />
				<input type="hidden" name="referer" id="referer" value="<?php echo $referer ?>" />
				<input type="hidden" name="id_nota" id="id_nota" value="<?php if(isset($nota)) print $nota['id_nota'] ?>" />
				<input type="text" style="font-size: 11px; border: 1px solid gray; width: 99%" id="_date" name="_date" readonly="readonly" value="<?php if(isset($_REQUEST['data'])) print(format_date($_REQUEST['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")); else if(isset($nota)) print (format_date($nota['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"));  else print date("d/m/Y") ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="4" style="padding-top: 20px; text-align: right;">
			<a id="manage_link" href="#" onclick="_submit()">Registra</a>
				<?php if(isset($nota)){ ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="del_note(<?php print $nota['id_nota'] ?>)">Cancella nota</a><?php } ?>
			</td>
		</tr>
		</table>
	</form>
</div>
</body>
</html>
