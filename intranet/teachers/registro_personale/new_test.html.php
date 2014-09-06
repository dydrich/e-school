<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/documents.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var manage_test = function(){
	// check mandatory fields
	msg = "Ci sono degli errori nel modulo.";
	index = 1;
	_submit = true;
	if($('#date_time').val() == ""){
		msg += "\n"+index+". Data e ora della verifica non presenti.";
		_submit = false;
		$('#td_data').css({color: "rgba(200, 6, 6, 1)"});
		index++;
	}
	else{
		$('#td_data').css({color: ""});
	}
	if($('#test').val() == ""){
		msg += "\n"+index+". Inserire una descrizione della prova (ad. es. Verifica di italiano).";
		_submit = false;
		$('#td_test').css({color: "rgba(200, 6, 6, 1)"});
		index++;
	}
	else{
		$('#td_test').css({color: ""});
	}
	if($('#subject').val() == ""){
		msg += "\n"+index+". Argomento della prova non presente.";
		_submit = false;
		$('#td_argomento').css({color: "rgba(200, 6, 6, 1)"});
		index++;
	}
	else{
		$('#td_argomento').css({color: ""});
	}
	if($('#tipo').val() == "0"){
		msg += "\n"+index+". Tipologia della prova non presente.";
		_submit = false;
		$('#td_tipo').css({color: "rgba(200, 6, 6, 1)"});
		index++;
	}
	else{
		$('#td_tipo').css({color: ""});
	}
	if(!_submit){
		alert(msg);
		return false;
	}

	$.ajax({
		type: "POST",
		url: 'test_manager.php',
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
				alert("Errore: riprova tra qualche minuto");
				console("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				alert(json.message);
				if ($('#do').val() == "update") {
					parent.$('#desc').text($('#test').val());
					parent.$('#datetm').text(json.date);
					parent.$('#ann').text($('#notes').val());
					parent.$('#top').text($('#subject').val());
					parent.$('#tp').text(json.tp);
				}
				else {
					var tr = document.createElement("tr");
					tr.setAttribute("style", "border-width: 1px 0 1px 0; border-style: solid; border-color: #CCCCCC");
					var td = document.createElement("td");
					td.setAttribute("style", "width: 25%; text-align: left; padding-left: 20px; font-weight: normal; ");
					td.appendChild(document.createTextNode(json.date.substr(0, json.date.length - 5)));
					tr.appendChild(td);

					td = document.createElement("td");
					td.setAttribute("style", "width: 10%; text-align: center; font-weight: normal ");
					td.appendChild(document.createTextNode("NO"));
					tr.appendChild(td);

					td = document.createElement("td");
					td.setAttribute("style", "width: 10%; text-align: center; font-weight: normal ");
					td.appendChild(document.createTextNode("0"));
					tr.appendChild(td);

					td = document.createElement("td");
					td.setAttribute("style", "width: 10%; text-align: center; font-weight: normal ");
					td.appendChild(document.createTextNode("--"));
					tr.appendChild(td);

					td = document.createElement("td");
					td.setAttribute("style", "width: 45%; text-align: center; font-weight: normal ");
					_a = document.createElement("a");
					_a.setAttribute("id", "test_"+json.id+"_1");
					_a.setAttribute("href", "test.php?idt="+json.id);
					_a.setAttribute("class", "test_link");
					_a.setAttribute("style", "font-weight: normal");
					_a.appendChild(document.createTextNode($('#test').val()+"::"+$('#subject').val()));
					td.appendChild(_a);
					tr.appendChild(td);

					parent.$('#tbody').prepend(tr);

				}
				parent.dialogclose();
			}
		}
	});
}

$(function(){
	$('#manage_link').button();
	$('#manage_link').click(function(event){
		event.preventDefault();
		manage_test();
	});
	$('#date_time').datetimepicker({
		dateFormat: "dd/mm/yy"
	});
	<?php if(isset($_REQUEST['test']) && $_REQUEST['test'] != 0){ ?>
	$('#do').val('update');
	<?php } ?>
});

</script>
<style>
* {font-size: 12px}
</style>
</head>
<body style="margin: 0; background-color: white">
<div id="" style='width: 100%; margin: 0; text-align: center; height: 100%'>
	<p style='text-align: center; padding-top: 5px; font-weight: bold' id='titolo'><?php echo $label ?></p>
	<form id='testform' action='' method='post'>
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
		<tr>
			<td id="td_data" style="width: 30%; font-weight: bold">Data *</td>
			<td style="width: 70%;">
				<input type="hidden" id="do" name="do" value="insert" />
				<input type="hidden" id="id_verifica" name="id_verifica" value="<?php echo $_REQUEST['test'] ?>" />
				<input type="text" id="date_time" name="date_time" style="width: 95%; font-size: 11px" readonly="readonly" value="<?php if($_REQUEST['test'] != 0) echo format_date(substr($test->getTestDate(), 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/")." ".substr($test->getTestDate(), 11, 5) ?>" />
			</td>
		</tr>
		<tr>
			<td id="td_test" style="width: 30%; font-weight: bold">Prova *</td>
			<td style=""><input type="text" id="test" name="test" class="form_input" style="width: 95%; font-size: 11px" value="<?php if($_REQUEST['test'] != 0) echo $test->getDescription() ?>" /></td>
		</tr>
		<tr>
			<td id="td_tipo" style="width: 30%; font-weight: bold">Tipologia *</td>
			<td style="">
			<select id="tipo" name="tipo" style="width: 95%; font-size: 11px">
			<option value="0">Scegli</option>
			<?php 
			while ($row = $res_prove->fetch_assoc()){
			?>
			<option value="<?php echo $row['id'] ?>" <?php if (isset($test) && $test->getType() == $row['id']) echo "selected" ?>><?php echo $row['tipologia'] ?></option>
			<?php } ?>
			</select>
			</td>
		</tr>
		<tr>
			<td id="td_argomento" style="width: 30%; font-weight: bold">Argomento *</td>
			<td style=""><textarea id="subject" name="subject" style="width: 95%; font-size: 11px; height: 30px"><?php if($_REQUEST['test'] != 0) print utf8_decode($test->getTopic()) ?></textarea></td>
		</tr>
		<tr>
			<td id="td_note" style="width: 30%; font-weight: bold">Note</td>
			<td style=""><textarea id="notes" name="notes" style="width: 95%; font-size: 11px; height: 30px"><?php if($_REQUEST['test'] != 0) print utf8_decode($test->getAnnotation()) ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 20px 15px 30px 0; text-align: right">
				<button id="manage_link">Registra</button>
			</td>
		</tr>
		</table>
	</form>
</div>
</body>
</html>
