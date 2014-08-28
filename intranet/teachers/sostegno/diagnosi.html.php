<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Diagnosi</title>
<link rel="stylesheet" href="../../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#terapia').click(function(event){
		$('#ter').toggle(600);
	});
});

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
		<div class="group_head">Dati clinici</div>
		<div id="not1" class="notification"></div>
		<form id="my_form" method="post" action="aggiorna_dati_nucleo.php" style="border: 1px solid rgba(30, 67, 137, .8); border-radius: 10px; margin-top: 30px; text-align: left; width: 80%; margin-left: auto; margin-right: auto">
		<table style="width: 90%; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
			<tr>
				<td style="width: 40%">Terapia</td>
				<td style="width: 60%">
				<label for="terapia">Attualmente in terapia</label>
					<input type="checkbox" id="terapia" name="terapia" value="1" <?php if ($_SESSION['__sp_student__']['dati']['terapia'] && $_SESSION['__sp_student__']['dati']['terapia'] == 1) echo "checked" ?> />
					<div id="ter" <?php if (!$_SESSION['__sp_student__']['dati']['terapia'] || $_SESSION['__sp_student__']['dati']['terapia'] != 1) echo "style='display: none'" ?>>
					Intervento specialistico di tipo: <br />
					<label for="neuro">Neuropsichiatrico</label>
					<input type="checkbox" id="neuro" name="neuro" value="1" <?php if ($_SESSION['__sp_student__']['dati']['tipo_terapia'] && $terapy[3] == 1) echo "checked" ?> /><br />
					<label for="psico">Psicologico</label>
					<input type="checkbox" id="psico" name="psico" value="1" <?php if ($_SESSION['__sp_student__']['dati']['tipo_terapia'] && $terapy[1] == 1) echo "checked" ?> /><br />
					<label for="orto">Ortofonico</label>
					<input type="checkbox" id="orto" name="orto" value="1" <?php if ($_SESSION['__sp_student__']['dati']['tipo_terapia'] && $terapy[0] == 1) echo "checked" ?> /><br />
					<label for="moto">Psicomotorio</label>
					<input type="checkbox" id="moto" name="moto" value="1" <?php if ($_SESSION['__sp_student__']['dati']['tipo_terapia'] && $terapy[2] == 1) echo "checked" ?> /><br />
					<label for="oth">Altro</label>
					<input type="text" id="oth" name="oth" style="width: 80%" value="<?php if ($_SESSION['__sp_student__']['dati']['tipo_terapia']) echo $terapy[4] ?>" /><br />
					</div>
				</td> 
			</tr>
			<tr>
				<td colspan="2">&nbsp;
					<input type="hidden" id="area" name="area" value="diagnosi" />
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
