<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
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
function upd_grade(sel, al, idv){
	req = new Ajax.Request('test_grades_manager.php',
			  {
			    	method:'post',
			    	parameters: {voto: sel.value, alunno: al, verifica: <?php print $_REQUEST['idt'] ?>, id_voto: idv},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore: riprova tra un po'. Dettaglio: "+dati[1]+" ---- "+dati[2]);
							return;
			      		}
			      		$('num_st').innerHTML = dati[2];
			      		$('avg').innerHTML = dati[1];
			      		//alert($('voto'+al).readAttribute('onchange'));
			      		if(sel.value == 0){
							$('voto'+al).writeAttribute("onchange", "upd_grade(this, "+al+", 0)");
			      		}
			      		else{
							if(idv == 0){
								$('voto'+al).writeAttribute("onchange", "upd_grade(this, "+al+", "+dati[3]+")");
							}
			      		}
			      		//alert($('voto'+al).readAttribute("onchange"));
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function delete_test(){
	delete_grades = true;
	if(!confirm("Vuoi cancellare la verifica? Saranno eliminati anche tutti i voti relativi")){
		return false;
	}
	req = new Ajax.Request('test_manager.php?do=delete',
			  {
			    	method:'post',
			    	parameters: {id_verifica: <?php print $_REQUEST['idt'] ?>},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore: riprova tra un po'. Dettaglio: "+dati[1]+" ---- "+dati[2]);
							return;
			      		}
			      		alert("Verifica cancellata");
			      		document.location.href = "tests.php";		      		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function update_test(){
	win = new Window({className: "mac_os_x", url: "new_test.php?test=<?php print $_REQUEST['idt'] ?>&referer=test",  width:400, height:240, zIndex: 100, resizable: true, title: "Gestione verifica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});	
	win.showCenter(true);
}
</script>
<style>
td { border: inherit; }
table.registro td {
	border: 0
}
</style>
</head> 
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<div style="text-align: center; font-size: 1em; font-weight: bold; margin: auto; width: 95%; text-transform: uppercase">Dettaglio verifica di <?php print $materia['materia'] ?> del <?php print format_date(substr($test['data_verifica'], 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></div>
<fieldset style="width: 95%; margin: auto; border-color: #CCCCCC; border-radius: 10px; background-color: rgba(211, 222, 199, 0.3)">
<legend style="margin-left: 15px; font-weight: bold">Dati verifica</legend>
<table style="border-collapse: collapse; width: 90%; margin-left: auto; margin-right: auto; margin-top: 10px; margin-bottom: 10px">
	<tr style="height: 25px; border-bottom: 1px solid gray">
		<td style="width: 10%; font-weight: bold">Data e ora</td>
		<td style="width: 20%; text-align: right;"><?php print utf8_encode($giorno_str) ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Alunni</td>
		<td style="width: 20%; font-weight: bold; text-align: right;" id="num_st"><?php print $count_alunni ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Media voto</td>
		<td style="width: 20%; font-weight: bold; text-align: right;" id="avg"><?php if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30) echo $voti_religione[round($avg)]; else echo round($avg, 2) ?></td>
		
	</tr>
	<tr style="height: 25px; border-bottom: 1px solid gray">
		<td style="width: 10%; font-weight: bold">Prova</td>
		<td style="width: 20%; text-align: right;"><?php print $test['prova'] ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Tipo</td>
		<td style="width: 20%; text-align: right;" id="arg"><?php print $test['tipo'] ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Note</td>
		<td style="width: 20%; text-align: right;"><?php print $test['note'] ?></td>
	</tr>
	<tr style="height: 25px; border-bottom: 1px solid gray">
		<td style="width: 10%; font-weight: bold">Argomento</td>
		<td colspan="7"><?php print $test['argomento'] ?></td>
	</tr>
	<tr style="height: 25px; border-bottom: 1px solid gray">
		<td style="width: 10%; font-weight: bold">Obiettivi</td>
		<td colspan="7"><?php echo $string_obj ?></td>
	</tr>
</table>
<div style="width: 90%; text-align: right; margin-right: auto; margin-left: auto; margin-bottom: 20px">
	<a href="#" onclick="update_test()" style="padding-right: 10px; text-transform: uppercase; text-decoration: none">Modifica dati</a>|
	<a href="test_goals.php?idv=<?php echo $test['id_verifica'] ?>" style="padding-left: 10px; padding-right: 10px; text-transform: uppercase; text-decoration: none">Obiettivi</a>|
	<a href="#" onclick="delete_test()" style="padding-left: 10px; text-transform: uppercase; text-decoration: none">Cancella verifica</a>
</div>
</fieldset>
<table class="registro" style="width: 98%; margin-left: auto; margin-right: auto; margin-top: 20px" id="det_table">
	<thead>
	<tr>
		<td colspan="6" style="text-align: center; text-decoration: underline; text-transform: uppercase; font-weight: bold; font-size: 1em; padding-bottom: 15px">Valutazioni</td>	
	</tr>
	</thead>
	<tbody>
	<tr style="border-bottom: 1px solid #cccccc">
	<?php
	$student_in_a_row = 3;
	foreach($alunni as $alunno){
	?>
		<td style="width: 22%; text-align: left; font-weight: normal; "><?php print $alunno['cognome']." ".$alunno['nome'] ?></td>
		<td style="width: 11%; text-align: center; font-weight: normal;">
			<select id="voto<?php print $alunno['id_alunno'] ?>" style="font-size: 11px; " onchange="upd_grade(this, <?php print $alunno['id_alunno'] ?>, <?php print $alunno['id_voto'] ?>)">
				<option value="0" <?php if($v == $alunno['voto']) print "selected='selected'" ?>>Assente</option>
				<?php
				if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
					foreach ($voti_religione as $k => $g){
				?>
				<option value='<?php echo $k ?>' <?php if($k == $alunno['voto']) print "selected='selected'" ?>><?php echo $g ?></option>
				<?php
					}
				}
				else{
					for($x = 100; $x > 9; $x -= 5){
						$v = $x / 10;
				?>
				<option value="<?php print $v ?>" <?php if($v == $alunno['voto']) print "selected='selected'" ?>><?php print $v ?></option>
				<?php 
					} 
				}
				?>
			</select>
		</td>
	<?php 
		$student_in_a_row--;
		if($student_in_a_row < 1){
			$student_in_a_row = 3;
	?>
	</tr>
	<tr style="border-bottom: 1px solid #cccccc">
	<?php 
		}
	}
	$tds = 3 - (count($alunni) % 3);
	for($i = 0; $i < $tds; $i++){
	?>
	<td></td>
	<td></td>
	<?php 
	}
	?>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="6" style="height: 40px; text-align: right"><a href="tests.php" style="text-transform: uppercase; text-decoration: none"><img src="../../../images/back.png" style="margin-right: 8px; position: relative; top: 5px" />Torna alle verifiche</a></td>
	</tr>
	</tfoot>
</table>
</div> 
<?php include "../footer.php" ?>
</body> 
</html>