<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Profilo dell'alunno</title>
<link rel="stylesheet" href="../reg.css" type="text/css" media="screen,projection" />
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
		<h2 style="text-align: center; padding-top: 10px">Profilo dell'alunno</h2>
		<div id="not1" class="notification"></div>
		<form id="my_form" method="post" action="aggiorna_dati_nucleo.php" style="border: 1px solid #666666; border-radius: 10px; margin-top: 30px; text-align: left; width: 80%; margin-left: auto; margin-right: auto">
		<table style="width: 90%; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
			<tr>
				<td style="width: 40%">Difficolt&agrave; prevalenti</td>
				<td style="width: 60%"><textarea name="diff" id="diff" style="width: 100%; height: 150px; font-size: 11px; border: 1px solid #AAAAAA"><?php if ($_SESSION['__sp_student__']['dati']['difficolta_prevalenti']) echo $_SESSION['__sp_student__']['dati']['difficolta_prevalenti'] ?></textarea></td> 
			</tr>
			<tr>
				<td style="width: 40%">Profilo dinamico-funzionale</td>
				<td style="width: 60%"><textarea name="profile" id="profile" style="width: 100%; height: 150px; font-size: 11px; border: 1px solid #AAAAAA"><?php if ($_SESSION['__sp_student__']['dati']['profilo']) echo $_SESSION['__sp_student__']['dati']['profilo'] ?></textarea></td> 
			</tr>
			<tr>
				<td colspan="2">&nbsp;
					<input type="hidden" id="area" name="area" value="profilo" />
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