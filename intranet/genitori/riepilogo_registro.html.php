<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var stid = 0;

var tempX = 0;
var tempY = 0;

function show_menu(e){
	if (IE) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('context_menu').style.top = parseInt(tempY)+"px";
    //alert(hid.style.top);
    $('context_menu').style.left = parseInt(tempX)+"px";
    $('context_menu').show();
    return false;
}

function dettaglio_assenze(id_alunno){
	$('context_menu').hide();
	w = window.open_centered("../teachers/registro_classe/elenco_assenze.php?alunno="+id_alunno, "elenco", 400, 500, "");
}

function delay(alunno, quadrimestre){
	$('context_menu').hide();
	w = window.open_centered("../teachers/registro_classe/dettaglio_rit_uscite.php?alunno="+alunno+"&q="+quadrimestre, "el", 400, 500, "");
}

document.observe("dom:loaded", function(){
	$('det_abs').observe("click", function(event){
		event.preventDefault();
		dettaglio_assenze(<?php print($_SESSION['__current_son__']) ?>);
	});
	$('rit').observe("click", function(event){
		event.preventDefault();
		document.forms[0].del.value = 1;
		delay(<?php echo $_SESSION['__current_son__'] ?>, <?php echo $q ?>);
	});
	$('context_menu').observe("mouseleave", function(event){
		$('context_menu').hide();
	});
	$('lnk').observe("click", function(event){
		event.preventDefault();
		show_menu(event);
	});
});

</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		<?php echo $label ?>
	</div>
	<div style="width: 95%; margin: 0 auto 20px auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 40%; float: left; position: relative; top: 30%">Alunno</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Assenze</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">% di assenze</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Ore assenza</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">% ore assenza</div>
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
			<td style="width: 35%; padding-left: 8px;"><a href="../../shared/no_js.php" id="lnk" style="color: black; font-weight: normal"><?php print $_SESSION['__sons__'][$_SESSION['__current_son__']][0] ?></a></td>
			<td style="width: 15%; text-align: center;"><?php print $assenze ?></td>
			<td style="width: 15%; text-align: center;"><?php print $perc_assenze ?> %</td>
			<td style="width: 15%; text-align: center;"><?php print $ore_assenza ?></td>
			<td style="width: 15%; text-align: center;"><?php print $perc_hours ?> %</td>
		</tr>
		<tr>
			<td colspan="5" style="height: 30px">&nbsp;</td>
		</tr>
		<tr style="text-align: center; height: 25px; background-color: rgba(211, 222, 199, 0.4)">
			<td style="font-weight: bold; padding-left: 8px; border-radius: 8px 0 0 8px">Dati complessivi</td>
			<td colspan="2" style="font-weight: bold">Giorni di lezione: <?php print $totale['giorni'] ?> (<span class="attention"><?php print $limite_giorni ?></span>)</td>
			<td colspan="2" style="font-weight: bold; border-radius: 0 8px 8px 0">Ore di lezione: <?php print $ore ?> (<span class="attention"><?php print $limite_ore ?></span>)</td>
		</tr>
		<tr>
			<td colspan="5" style="height: 30px">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" style="margin: 30px auto 0 auto; text-align: center; padding-right: 10px; height: 35px; border-width: 1px 0 1px 0; border-style: solid; border-color: rgba(211, 222, 199, 0.6)">
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
<!-- menu contestuale -->
    <div id="context_menu" style="position: absolute; width: 210px; height: 50px; display: none; ">
    	<a style="font-weight: normal; font-size: 11px" href="../../shared/no_js.php" id="det_abs">Elenco assenze</a><br />
    	<a style="font-weight: normal; font-size: 11px" href="../../shared/no_js.php" id="rit">Elenco ritardi e uscite anticipate</a><br />
    </div>
<!-- fine menu contestuale -->
</body>
</html>
