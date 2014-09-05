<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Nucleo familiare</title>
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
function registra(){
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
		<div class="group_head">Nucleo familiare</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" method="post" action="aggiorna_dati_nucleo.php" style="border: 1px solid rgba(30, 67, 137, .8); border-radius: 10px; margin-top: 30px; text-align: left; width: 460px; margin-left: auto; margin-right: auto">
		<table style="width: 400px; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
			<tr>
				<td style="width: 60%">Padre</td>
				<td style="width: 40%"><input type="text" name="father" id="father" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if ($_SESSION['__sp_student__']['dati']['padre']) echo $_SESSION['__sp_student__']['dati']['padre'] ?>" /></td> 
			</tr>
			<tr>
				<td style="width: 60%">Madre</td>
				<td style="width: 40%"><input type="text" name="mother" id="mother" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if ($_SESSION['__sp_student__']['dati']['madre']) echo $_SESSION['__sp_student__']['dati']['madre'] ?>" /></td> 
			</tr>
			<tr>
			<tr>
				<td style="width: 60%">Fratelli e sorelle</td>
				<td style="width: 40%"><textarea name="brot" id="brot" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA"><?php if ($_SESSION['__sp_student__']['dati']['fratelli_sorelle']) echo $_SESSION['__sp_student__']['dati']['fratelli_sorelle'] ?></textarea></td> 
			</tr>
			<tr>
				<td style="width: 60%">Altro</td>
				<td style="width: 40%"><textarea name="oth" id="oth" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA"><?php if ($_SESSION['__sp_student__']['dati']['altro']) echo $_SESSION['__sp_student__']['dati']['altro'] ?></textarea></td> 
			</tr>
			<tr>
				<td colspan="2">&nbsp;
					<input type="hidden" id="area" name="area" value="nucleo" />
					<input type="hidden" id="idd" name="idd" value="<?php echo $idd ?>" />
				</td>				
			</tr>
			<tr>
				<td colspan="2" style="text-align: right; margin-right: 50px">
					<a href="#" onclick="registra()" class="standard_link">Registra</a>
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
