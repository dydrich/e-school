<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Installazione software</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/site_themes/indigo/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/site_themes/indigo/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
	var _submit = function(){
		var url = "init.php";

		$.ajax({
			type: "POST",
			url: url,
			data: $('#form1').serialize(true),
			dataType: 'json',
			error: function() {
				j_alert("error", "Errore di trasmissione dei dati");
			},
			succes: function() {

			},
			complete: function(data){
				r = data.responseText;
				if(r == "null"){
					return false;
				}
				var json = $.parseJSON(r);
				if (json.status == "ko"){
					alert(json.message);
					console.log(json.message);
				}
				else if(json.status == "kosql") {
					$('#error_space').css({border: '1px solid red', color: 'red', fontWeight: 'bold'});
					$('#error_space').html(json.message);

					return;
				}
				else {
					$('#go_link').html("<a href='install.php?step=3' class='nav_link_last material_link'>Avanti</a>");
					$('#error_space').css({border: '0'});
					$('#error_space').text("");
					j_alert("alert", "Operazione completata. Puoi passare all'ultima fase: inserimento dei dati essenziali");
				}
			}
		});
	};

	$(function(){
		$('#sub').click(function(event){
			event.preventDefault();
			_submit();
		});
	});
	</script>
<style>
label{font-weight: bold}
tr{height: 25px}
</style>
</head>
<body>
<?php $drawer_label = "Parametri di connessione"; ?>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "working.php" ?>
	</div>
	<div id="left_col">
		<form method="post" action="init.php" id="form1" class="no_border">
			<table style="width: 90%; margin-right: auto; margin-left: auto; border-collapse: collapse">
				<tr>
					<td style="font-weight: bold; font-size: 15px; text-align: center; text-shadow: 2px 2px 1px #eee" colspan="3">Creazione e popolamento del database: utente amministratore</td>
				</tr>
				<tr style="height: 30px">
					<td colspan="3" id="error_space"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="fname">Nome</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="fname" name="fname" style="width: 95%" autofocus /></td>
					<td style="width: 40%"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="lname">Cognome</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="lname" name="lname" style="width: 95%" /></td>
					<td style="width: 40%"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="email">Email</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="email" name="email" style="width: 95%" /></td>
					<td style="width: 40%"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="pwd">Password</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="pwd" name="pwd" style="width: 95%" /></td>
					<td style="width: 40%"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="hidden" name="step" id="step" value="2" /></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right">
						<a href="index.php?step=2" class="nav_link_first material_link">Indietro</a>
						<span id="go_link"><a href="../shared/no_js.php" id="sub" class="nav_link_last material_link">Registra</a></span>
					</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</form>

	</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
