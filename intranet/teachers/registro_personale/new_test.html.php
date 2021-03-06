<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di classe</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
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
				j_alert("error", msg);
				return false;
			}

			$.ajax({
				type: "POST",
				url: 'test_manager.php',
				data:  $('#testform').serialize(true),
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {
					j_alert("alert", "ok");
				},
				complete: function(data, status){
					r = data.responseText;
					var json = $.parseJSON(r);
					if(json.status == "kosql"){
						j_alert("error", "Errore: riprova tra qualche minuto");
						console("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
						return;
					}
					else {
						j_alert("alert", json.message);
						if ($('#do').val() == "update") {
							parent.$('#desc').text($('#test').val());
							parent.$('#datetm').text(json.date);
							parent.$('#ann').text($('#notes').val());
							parent.$('#top').text($('#subject').val());
							parent.$('#tp').text(json.tp);
						}
						else {
							//alert("else");
							_a = document.createElement("a");
							_a.setAttribute("id", "test_"+json.id+"_1");
							_a.setAttribute("href", "test.php?idt="+json.id);
							_a.setAttribute("class", "test_link");
							_a.setAttribute("style", "font-weight: normal");

							var card = document.createElement("div");
							card.setAttribute("class", "card");

							var card_title = document.createElement("div");
							card_title.setAttribute("class", "card_title");
							card_title.appendChild(document.createTextNode(json.date_string+" - "+$('#test').val()+"::"+$('#subject').val()));

							var right_div = document.createElement("div");
							right_div.setAttribute("style", "float: right; margin-right: 20px; color: #1E4389");
							right_div.appendChild(document.createTextNode("Media voto: -- "));
							var sp = document.createElement("span");
							sp.setAttribute("style", "font-weight: normal; text-transform: none");
							sp.appendChild(document.createTextNode("(valutati 0 alunni)"));
							right_div.appendChild(sp);

							card_title.appendChild(right_div);
							card.appendChild(card_title);

							_a.appendChild(card);

							parent.$('#card_container').prepend(_a);
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
			$('#date_time').datepicker({
				dateFormat: "dd/mm/yy"
			});
			<?php if(isset($_REQUEST['test']) && $_REQUEST['test'] != 0){ ?>
			$('#do').val('update');
			<?php } ?>
		});

	</script>
</head>
<body style="margin: 0; background-color: white">
<div id="popup_main" style='width: 100%; margin: 0; text-align: center; height: 100%; background-color: white; padding-top: 10px'>
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
			<td style=""><textarea id="subject" name="subject" style="width: 95%; font-size: 11px; height: 30px"><?php if($_REQUEST['test'] != 0) print $test->getTopic() ?></textarea></td>
		</tr>
		<tr>
			<td id="td_note" style="width: 30%; font-weight: bold">Note</td>
			<td style=""><textarea id="notes" name="notes" style="width: 95%; font-size: 11px; height: 30px"><?php if($_REQUEST['test'] != 0) print $test->getAnnotation() ?></textarea></td>
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
