<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Statistiche</title>
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
var stid = 0;

var tempX = 0;
var tempY = 0;

function show_menu(e, _stid){
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
    $('#context_menu').css({'top': parseInt(tempY)+"px"});
    //alert(hid.style.top);
    $('#context_menu').css({'left': parseInt(tempX)+"px"});
    $('#context_menu').show();
    stid = _stid;
    return false;
}

var dettaglio_assenze = function(f_id, q){
	$('#context_menu').hide();
	if (f_id == 0) {
		$('#iframe').attr("src", "elenco_assenze.php?alunno="+stid);
		lab_title = "Elenco assenze";
	}
	else {
		$('#iframe').attr("src", "dettaglio_rit_uscite.php?alunno="+stid+"&q="+q);
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

$(function(){
	$('context_menu').mouseleave(function(event){
		event.preventDefault();
		$('#context_menu').hide();
	});
});

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<form>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="5" style="text-align: center; font-weight: bold">
		<?php print $_SESSION['__classe__']->to_string()." - Statistiche di presenza "; if($q == 1) print "primo quadrimestre"; else if($q == 2) print "secondo quadrimestre"; else print "totale anno scolastico" ?>
		<span style="margin-left: 5px">[ </span><a href="pdf_stats.php?q=<?php echo $q ?>">PDF</a><span> ]</span>
	</td>
</tr>
<tr class="head_tr_no_bg">
	<td style="text-align: left; font-weight: bold; padding-left: 8px">Dati complessivi</td>
	<td colspan="2" style="text-align: center; font-weight: bold">Giorni di lezione: <?php print $totali['giorni'] ?> (<span class="attention"><?php print $totali['limite_giorni'] ?></span>)</td>
	<td colspan="2" style="text-align: center; font-weight: bold">Ore di lezione: <?php print $totali['ore']->toString(RBTime::$RBTIME_SHORT) ?> (<span class="attention"><?php print $totali['limite_ore']->toString(RBTime::$RBTIME_SHORT) ?></span>)</td>
</tr>
<tr class="title_tr">
	<td style="width: 40%; font-weight: bold; padding-left: 8px">Alunno</td>
	<td style="width: 15%; text-align: center; font-weight: bold">Assenze</td>
	<td style="width: 15%; text-align: center; font-weight: bold">% assenze</td>
	<td style="width: 15%; text-align: center; font-weight: bold">Ore assenza</td>
	<td style="width: 15%; text-align: center; font-weight: bold">% ore assenza</td>
</tr>
</thead>
<tbody>
<?php 
$idx = 0;
foreach ($presence as $k => $row){ 
	$perc_day = round((($row['absences'] / $totali['giorni']) * 100), 2);
    $absences = new RBTime(0, 0, 0);
    $absences->setTime($totali['ore']->getTime() - $row['presence']->getTime());
    $perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);
    if($perc_day == 0){
		$perc_day = "--";
	}
	else{
		$perc_day .= "%";
	}
	if($perc_hour == 0){
		$perc_hour = "--";
	}
	else{
		$perc_hour .= "%";
	}
	$background = "";
?>
<tr>
	<td style="width: 35%; padding-left: 8px"><a href="#" onclick="show_menu(event, <?php print $k ?>)" style="font-weight: normal; color: inherit"><?php print stripslashes($row['name']) ?></a></td>
	<td style="width: 15%; text-align: center"><?php print $row['absences'] ?></td>
	<td style="width: 15%; text-align: center;" <?php if($perc_day > 24.99) print("class='attention _bold'") ?>><?php print $perc_day ?></td>
	<td style="width: 15%; text-align: center"><?php print ($absences->getTime() > 0) ? $absences->toString(RBTime::$RBTIME_SHORT) : "--" ?></td>
	<td style="width: 15%; text-align: center;" <?php if($perc_hour > 24.99) print("class='attention _bold'") ?>><?php print $perc_hour ?></td>
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<tr  class="nav_tr">
		<td colspan="5" style="text-align: center; height: 40px">
			<a href="stats.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="stats.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
			</a>
			<a href="stats.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
			</a>
		</td>
	</tr>
</tfoot>
</table>
</form>
<p></p>
</div>
<!-- menu contestuale -->
    <div id="context_menu" style="position: absolute; width: 210px; height: 50px; display: none; ">
    	<a style="font-weight: normal" href="#" onclick="dettaglio_assenze(0, 0)">Elenco assenze</a><br />
    	<a style="font-weight: normal" href="#" onclick="dettaglio_assenze(1, <?php print $q ?>)">Elenco ritardi e uscite anticipate</a><br />
    </div>
<!-- fine menu contestuale -->
<?php include "../footer.php" ?>
<div id="abs_pop" style="display: none">
	<iframe id="iframe" src="new_note.php" style="width: 100%; height: 450px; margin: 0 auto; padding: 0"></iframe>
</div>
</body>
</html>
