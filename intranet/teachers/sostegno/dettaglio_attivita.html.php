<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Attivit&agrave;</title>
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#day').datepicker({
		dateFormat: "dd/mm/yy",
		currentText: "Oggi",
		closeText: "Chiudi"
	});
});

function registra(){
	if ($('#day').val() == "" || $('#att').val() == ""){
		alert("Data e lavoro svolto sono campi obbligatori");
		return false;
	}
	$.ajax({
		type: "POST",
		url: "aggiorna_dati_studente.php",
		data: $('#my_form').serialize(),
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
				$('#not1').text(json.message);
				$('#not1').show(1000);
				setTimeout("$('#not1').hide(1000)", 2000);
				setTimeout("document.location.href='attivita.php'", 1000);
			}
		}
	});
}
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "menu_sostegno.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head"><?php echo $label ?></div>
		<div id="not1" class="notification"></div>
		<form id="my_form" method="post" action="aggiorna_dati_nucleo.php" style="border: 1px solid rgba(30, 67, 137, .8); border-radius: 10px; margin-top: 30px; text-align: left; width: 90%; margin-left: auto; margin-right: auto">
		<table style="width: 90%; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
			<tr>
				<td style="width: 40%">Data</td>
				<td style="width: 60%"><input type="text" name="day" id="day" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if ($att) echo $att['data']; else if ($_REQUEST['data']) echo format_date($_REQUEST['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" /></td> 
			</tr>
			<tr>
				<td style="width: 40%">Attivit&agrave; svolta</td>
				<td style="width: 60%"><textarea name="att" id="att" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA; height: 80px"><?php if ($att) echo $att['attivita'] ?></textarea></td> 
			</tr>
			<tr>
				<td colspan="2">&nbsp;
					<input type="hidden" id="area" name="area" value="attivita" />
					<input type="hidden" id="id" name="id" value="<?php echo $_GET['id'] ?>" />
				</td>				
			</tr>
			<tr>
				<td colspan="2" style="text-align: right; margin-right: 50px">
					<a href="#" onclick="registra()" class="standard_link">Registra</a>
					<?php if ($_GET['id'] != 0): ?>
					<span style="margin: 5px">|</span>
					<a href="#" onclick="$('#area').val('delatt'); registra()" class="standard_link">Elimina</a>
					<?php endif; ?>
				</td> 
			</tr>
		</table>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
