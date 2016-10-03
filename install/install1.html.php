<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Installazione software</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/site_themes/indigo/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/site_themes/indigo/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		var _submit = function(){
			msg = "Errori: \n";
			index = 1;
			go = true;
			if($('#host').val() == ""){
				msg += index+". host empty\n";
				go = false;
				index++;
			}
			if($('#user').val() == ""){
				msg += index+". user empty\n";
				go = false;
				index++;
			}
			if($('#password').val() == ""){
				msg += index+". password empty\n";
				go = false;
				index++;
			}
			if($('#db').val() == ""){
				msg += index+". db empty\n";
				go = false;
				index++;
			}
			if($('#port').val() == ""){
				msg += index+". port empty\n";
				go = false;
				index++;
			}
			if(!go){
				j_alert("error", msg);
				return false;
			}
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
						$('#error_space').css({border: '1px solid red', color: 'red', fontWeight: 'bold', textAlign: 'center'});
						$('#error_space').text(json.error);
						switch(json.errno){
							case "2005":
								$('#server_error').css({color: 'red', fontWeight: 'bold', textAlign: 'center'});
								$('#server_error').text(json.error);
								$('#user_error').text('');
								$('#db_error').text('');
								$('#port_error').text('');
								break;
							case  "1045":
								$('#user_error').css({color: 'red', fontWeight: 'bold', textAlign: 'center'});
								$('#user_error').text(json.error);
								$('#server_error').text('');
								$('#db_error').text('');
								$('#port_error').text('');
								break;
							case  "1049":
								$('#db_error').css({color: 'red', fontWeight: 'bold', textAlign: 'center'});
								$('#db_error').text(json.error);
								$('#user_error').text('');
								$('#server_error').text('');
								$('#port_error').text('');
								break;
							case  "2003":
								$('#port_error').css({color: 'red', fontWeight: 'bold', textAlign: 'center'});
								$('#port_error').text(json.error);
								$('#user_error').text('');
								$('#db_error').text('');
								$('#server_error').text('');
								break;
						}
						return;
					}
					else {
						$('go_link').html("<a href='install.php?step=2' class='nav_link_last'>Avanti</a>");
							$('#error_space').css({border: '0'});
							$('#error_space').text("");
							$('#db_error').text('');
							$('#user_error').text('');
							$('#server_error').text('');
							$('#port_error').text('');
							j_alert("alert", "Connessione completata. Puoi proseguire col passo successivo: creazione del database");
						setTimeout(function(){
							document.location.href = "install.php?step=2";
						}, 2000);
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#sub').click(function(event){
				event.preventDefault();
				_submit();
			});
			<?php
			if($warning){
			?>
			$('#error_space').css({border: '1px solid red', color: 'red', fontWeight: 'bold', textAlign: 'center'});
			$('#error_space').html("<?php echo $warning ?>");
			$('#sub').removeClass('nav_link_last');
			$('#sub').addClass('nav_link');
			_a = document.createElement("a");
			_a.attr("href", "install.php?step=2");
			_a.addClass("nav_link_last material link");
			_a.appendChild(document.createTextNode("Avanti"));
			$('#go_link').appendChild(_a);
			<?php } ?>
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
				<tr style="height: 30px">
					<td colspan="3" id="error_space"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="host">Server</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="host" name="host" style="width: 95%" autofocus /></td>
					<td style="width: 40%" id="server_error"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="db">Nome database</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="db" name="db" style="width: 95%" /></td>
					<td style="width: 40%" id="db_error"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="user">Utente</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="user" name="user" style="width: 95%" /></td>
					<td style="width: 40%" id="user_error" rowspan="2"></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="password">Password</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="password" name="password" style="width: 95%" /></td>
				</tr>
				<tr>
					<td style=" width: 20%"><label for="port">Porta</label></td>
					<td style="width: 40%; text-align: right"><input type="text" id="port" name="port" style="width: 95%" value="3306" /></td>
					<td style="width: 40%" id="port_error"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="hidden" name="step" id="step" value="1" /></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right">
						<a href="index.php" class="nav_link_first material_link">Indietro</a>
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
