<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
function dettaglio_assenze(id_alunno){
	w = window.open_centered("../teachers/registro_classe/elenco_assenze.php?alunno="+id_alunno, "elenco", 400, 500, "");
}

function delay(alunno, quadrimestre){
	w = window.open_centered("../teachers/registro_classe/dettaglio_rit_uscite.php?alunno="+alunno+"&q="+quadrimestre, "el", 400, 500, "");
}
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		<?php echo $label ?>
	</div>
	<div style="width: 95%; margin: 0 auto 20px auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 30%; float: left; position: relative; top: 30%">&nbsp;</div>
		<div style="width: 35%; float: left; position: relative; top: 30%">Giorni di lezione</div>
		<div style="width: 35%; float: left; position: relative; top: 30%">Ore di lezione</div>
	</div>
	<table style="width: 95%; border-collapse: collapse; margin: auto">
<?php  
$limite_giorni = floor($totale['giorni'] / 4);
$secondi = $totale['ore']%60;
$tot_min = ($totale['ore'] - $secondi) / 60;
list($ore, $minuti) = explode(":", minutes2hours($tot_min, ""));
$tot_ore = "$ore:$minuti";
list($ore2, $minuti2, $secondi2) = explode(":", $totale['limite_ore']);
$limite_ore = $ore2.":".$minuti2;
$idx = 0;
$assenze = $totale['giorni'] - $al['giorni'];
$perc_assenze = "--";
if($totale['giorni'] > 0){
	$perc_assenze = round((($assenze / $totale['giorni']) * 100), 2);
}

/**
 * calcolo della percentuale oraria di assenze 
*/
// numero totale di ore di lezione (in secondi)
$tot_hours = $totale['ore'];
// ore di assenza (in secondi)
$abs_hours = $al['ore_assenza'];
$perc_hours = "--";
if($tot_hours > 0){
	$perc_hours = round((($abs_hours / $tot_hours) * 100), 2);
}
// formattazione ore assenza
$abs_sec = $abs_hours%60;
$t_m = $abs_hours - $abs_sec;
$t_m /= 60;
$ore_assenza = minutes2hours($t_m, "-");
?>
		<tr style="border-bottom: 1px solid rgba(211, 222, 199, 0.6); text-align: center; height: 20px">
			<td style="padding-left: 20px; width: 30%; font-weight: bold">Le tue assenze</td>
			<td style="width: 35%; "><?php if($assenze > 0) print("<a style='text-decoration: none' href='#' onclick='dettaglio_assenze(".$_SESSION['__user__']->getUid().")'>") ?><?php print $assenze ?> (<?php print $perc_assenze ?> %)<?php if($assenze > 0) print("</a>") ?></td>	
			<td style="width: 35%; "><?php if($ore_assenza > 0) print("<a style='text-decoration: none' href='#' onclick='delay(".$_SESSION['__user__']->getUid().", \"".$q."\")'>") ?><?php print $ore_assenza ?> (<?php print $perc_hours ?> %)<?php if($ore_assenza > 0) print("</a>") ?></td>
		</tr>
		<tr style="border-bottom: 1px solid rgba(211, 222, 199, 0.6); text-align: center; height: 20px">
			<td style="padding-left: 20px; width: 30%; font-weight: bold">Totale classe</td>
			<td style="width: 35%; "><?php print $totale['giorni'] ?> (<span class="attention"><?php print $limite_giorni ?></span>)</td>	
			<td style="width: 35%; "><?php print $tot_ore ?> (<span class="attention"><?php print $limite_ore ?></span>)</td>
		</tr>
		<tr>
			<td colspan="3" style="height: 30px"></td>
		</tr>
		<tr>
			<td colspan="3" style="margin: 30px auto 0 auto; text-align: center; padding-right: 10px; height: 35px; border-width: 1px 0 1px 0; border-style: solid; border-color: rgba(211, 222, 199, 0.6)">
				<a href="riepilogo_registro.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />1 Quadrimestre
				</a>
				<a href="riepilogo_registro.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
					<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />2 Quadrimestre
				</a>
				<a href="riepilogo_registro.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />Totale
				</a>
			</td>
		</tr>
	</table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
