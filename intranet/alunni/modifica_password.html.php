<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/md5-min.js"></script>
<script type="text/javascript">
	$(function(){
		load_jalert();
	});
	var registra = function(){
		var patt = /[^a-zA-Z0-9]/;
		if($('#new_pwd').val() == ""){
			alert("Password non valida.");
			return false;
		}
		else if($('#new_pwd').val().match(patt)){
			alert("Password non valida: sono ammessi solo lettere e numeri");
			return false;
		}
		if($('#new_pwd').val() != $('#cfm_pwd').val()){
			alert("Le password inserite sono differenti. Ricontrolla.");
			return false;
		}
		p = hex_md5($('#new_pwd').val());
		// fake password
		$('#new_pwd').val("calatafimi");
		$.ajax({
			type: "POST",
			url: '../../shared/account_manager.php',
			data: {new_pwd: p, action: "change_personal_password"},
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
				if (json.status == "kosql"){
					alert(json.message);
					console.log(json.dbg_message);
				}
				else if(json.status == "ko") {
					j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
					return;
				}
				else {
					j_alert("alert", json.message);
				}
			}
		});
	}
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "profile_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Modifica password
	</div>
	<form id="my_form" method="post" action="../../shared/account_manager.php" style="border: 1px solid #666666; border-radius: 10px; margin-top: 30px; text-align: left; width: 460px; margin-left: auto; margin-right: auto">
	<table style="width: 400px; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
		<tr>
			<td style="width: 60%">Nuova password</td>
			<td style="width: 40%"><input type="password" name="new_pwd" id="new_pwd" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" /></td> 
		</tr>
		<tr>
			<td style="width: 60%">Conferma password</td>
			<td style="width: 40%"><input type="password" name="cfm_pwd" id="cfm_pwd" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" /></td> 
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td> 
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; margin-right: 50px">
				<a href="#" onclick="registra()" style="text-decoration: none; text-transform: uppercase">Registra</a>
			</td> 
		</tr>
	</table>
	</form>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
