<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe_popup.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="../../../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
<script type="text/javascript">
function update_avg(st){
	var num_voti = <?php print $res_voti->num_rows ?>;
	var pesi = [];
	for(var i = 0; i < num_voti; i++){
		var fld = $('pound'+i);
		pesi.push(fld.value);
		//alert(fld.value);
	}
	var str_pesi = pesi.join(",");
	//alert(st);
	//alert(str_pesi);
	var req = new Ajax.Request('averages.php',
			  {
			    	method:'post',
			    	parameters: {voti: st, pesi: str_pesi},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		if(dati[0] == "ko"){
							//alert(dati[1]);
							return;
			      		}
			      		if(dati[1] < 6){
			      			$('avg').style.color = "red";
			      		}
			      		$('avg').innerHTML = dati[1];
			      		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
<style>
* {font-size: 11px}
</style>
</head>
<body onload="parent.avg_win.updateHeight()">
<div id="main">
	<p style='text-align: center; padding-top: 20px; font-weight: bold' id='titolo'></p>
	<form id='avgform' action='averages.php?do=do' method='post' onsubmit='return check_form(this); '>
		<table style='text-align: left; width: 95%; margin: auto; border-collapse: collapse' id='att'>
			<tr style="border-bottom: 1px solid">		
				<td style='width: 10%'>Voto</td>	
				<td style='width: 20%; text-align: center'>Data</td>	
				<td style='width: 50%; text-align: center'>Prova</td>	
				<td style='width: 20%; '>Peso (&lt;100)</td>
			</tr>
			<?php 
			$vt = 0;
			$background = "";
			$dx = 0;
			$res_voti->data_seek(0); 
			reset($array_voti); 
			while($_row = $res_voti->fetch_assoc()){ 	
				if($dx % 2)		
					$background = "background-color: #e8eaec";	
				else		
					$background = "";
			?>
			<tr>		
				<td style='width: 10%; text-align: right; padding-right: 10px; <?php print $background ?>'><?php print $_row['voto'] ?></td>	
				<td style='width: 20%; text-align: center; <?php print $background ?>'><?php print format_date($_row['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, '/') ?></td>	
				<td style='width: 50%; padding-left: 10px; <?php print $background ?>'><?php print $_row['descrizione'] ?></td>	
				<td style='width: 20%; <?php print $background ?>'>
					<input onchange='update_avg("<?php print join(',', $array_voti) ?>")' style='width: 90%; border: 1px solid gray; font-size: 11px; margin: auto' type='text' value='1' id='pound<?php print $dx ?>' maxlength='2' />
				</td>
			</tr>
			<?php 	
				$dx++;	
				$vt += $_row['voto'];	
			}
			$media_voto = round(($vt / $res_voti->num_rows), 2);
			?>
			<tr>	
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>	
				<td colspan='4' style='font-weight: bold; font-size: 1.1em'>
					Media ponderata: <span id='avg' style='<?php if($media_voto < 6) print('color: red') ?>'><?php print $media_voto ?></span>
				</td>
			</tr>
			<tr>	
				<td colspan='4'>&nbsp;<input type='hidden' id='string' name='string' /></td>
			</tr>
		</table>
	</form>
</div>
</body>
</html>