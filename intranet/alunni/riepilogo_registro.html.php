<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
	var stid = 0;

	var dettaglio_assenze = function(f_id, q){
		$('#context_menu').hide();
		if (f_id == 0) {
			$('#iframe').attr("src", "../teachers/registro_classe/elenco_assenze.php?alunno=<?php echo $_SESSION['__user__']->getUid() ?>");
			lab_title = "Elenco assenze";
		}
		else {
			$('#iframe').attr("src", "../teachers/registro_classe/dettaglio_rit_uscite.php?alunno=<?php echo $_SESSION['__user__']->getUid() ?>&q="+q);
			lab_title = "Ritardi";
		}
		$('#abs_pop').dialog({
			autoOpen: true,
			show: {
				effect: "appear",
				duration: 500
			},
			hide: {
				effect: "slide",
				duration: 300
			},
			modal: true,
			width: 450,
			title: lab_title,
			open: function(event, ui){

			}
		});
		//var w = new Window({className: "mac_os_x",  width:400, zIndex: 100, resizable: true, title: "Elenco assenze", url: "elenco_assenze.php?alunno="+stid, showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
		//w.showCenter(true);
	}

	var dialogclose = function(){
		$('#abs_pop').dialog("close");
	};

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
	<div class="group_head">
		<?php echo $label ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 30%; float: left; position: relative; top: 30%">&nbsp;</div>
		<div style="width: 35%; float: left; position: relative; top: 25%">Giorni di lezione</div>
		<div style="width: 35%; float: left; position: relative; top: 25%">Ore di lezione</div>
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
		<tr>
			<td colspan="3" class="admin_void">&nbsp;</td>
		</tr>
		<tr class="manager_row_small _center">
			<td style="padding-left: 20px; width: 30%; font-weight: bold">Le tue assenze</td>
			<td style="width: 35%; "><?php if($assenze > 0) print("<a style='text-decoration: none' href='#' onclick='dettaglio_assenze(0, 0)'>") ?><?php print $assenze ?> (<?php print $perc_assenze ?> %)<?php if($assenze > 0) print("</a>") ?></td>
			<td style="width: 35%; "><?php if($ore_assenza > 0) print("<a style='text-decoration: none' href='#' onclick='dettaglio_assenze(1, \"".$q."\")'>") ?><?php print $ore_assenza ?> (<?php print $perc_hours ?> %)<?php if($ore_assenza > 0) print("</a>") ?></td>
		</tr>
		<tr class="manager_row_small _center">
			<td style="padding-left: 20px; width: 30%; font-weight: bold">Totale classe</td>
			<td style="width: 35%; "><?php print $totale['giorni'] ?> (<span class="attention"><?php print $limite_giorni ?></span>)</td>	
			<td style="width: 35%; "><?php print $tot_ore ?> (<span class="attention"><?php print $limite_ore ?></span>)</td>
		</tr>
		<tr>
			<td colspan="3" style="height: 30px"></td>
		</tr>
		<tr>
			<td colspan="3" style="margin: 30px auto 0 auto; text-align: center; padding-right: 10px; height: 35px; border-width: 1px 0 1px 0; border-style: solid; border-color: rgba(30, 67, 137, .3);">
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
<div id="abs_pop" style="display: none">
	<iframe id="iframe" src="../teachers/registro_classe/elenco_assenze.php" style="width: 100%; height: 450px; margin: 0 auto; padding: 0"></iframe>
</div>
</body>
</html>
