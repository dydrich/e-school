<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_print.css" type="text/css" media="print" />
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
<?php echo $change_subject->getJavascript() ?>

function change_subject(id){
	document.location.href="absences.php?subject="+id;
}

function fai(event){
	if (IE) { // grab the x-y pos.s if browser is IE
        asse_x = event.clientX + document.body.scrollLeft;
        asse_y = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        asse_x = event.pageX;
        asse_y = event.pageY;
    }  
	//setTimeout('get_caption('+asse_x+', '+asse_y+')', 1000);
	get_caption(asse_x, asse_y);
}

function get_caption(x, y){
	if($('pop').style.display == "inline"){
		$('pop').style.display = "none";
		return;
	}
   
	$('pop').innerHTML = str;
	$('pop').style.left = parseInt(asse_x)+"px";
	$('pop').style.top = parseInt(asse_y)+"px";
	$('pop').style.display = "inline";
}

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<!-- div nascosto, per la scelta della materia -->
<?php $change_subject->toHTML() ?>
<form action="student.php" method="post">
<?php 
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="8" style="text-align: center; font-weight: bold"><?php print $_SESSION['__current_year__']->to_string() ?> - Assenze orarie per materia</td>
</tr>
<tr class="head_tr_no_bg">
	<td colspan="3" style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="5" style="text-align: center; ">Materia: <span id="uscita" style="font-weight: bold; "><?php $change_subject->printLink() ?></span></td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: 40%; font-weight: bold; padding-left: 8px">Alunno</td>
	<td colspan="2" style="width: 20%; text-align: center; font-weight: bold">Totale: <?php print $hours_count ?></td>
	<td colspan="2" style="width: 20%; text-align: center; font-weight: bold">1 quadrimestre: <?php print $q1_hours_count ?></td>
	<td colspan="2" style="width: 20%; text-align: center; font-weight: bold">2 quadrimestre: <?php print $q2_hours_count ?></td>
</tr>
<tr class="title_tr">
	<td style="width: 10%; text-align: center; font-weight: bold">Ore assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">% assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">Ore assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">% assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">Ore assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">% assenza</td>
</tr>
</thead>
<tbody>
<?php 
$idx = 0;
foreach($students as $student){
	$background = "";
	if($idx%2)
		$background = "background-color: #e8eaec";

	/*
	 * 1. trasformo i totali ore in minuti
	 * 2. calcolo le percentuali di assenza
	 * trasformo i minuti di assenza nel formato ore:minuti
	 */
	$tot_hours = $hours_count * 60;
	$tot_q1 = $q1_hours_count * 60;
	$tot_q2 = $q2_hours_count * 60;
	
	$tot_absence = minutes2hours($tot_hours, "/");
	$q1_absence = minutes2hours($tot_q1, "/");
	$q2_absence = minutes2hours($tot_q2, "/"); 
	
	$st_absence = minutes2hours($student['absence_time'], "/");
	$st_1q_absence = minutes2hours($student['absence_time_1q'], "/");
	if(date("Y-m-d") > $fine_q){
		$st_2q_absence = minutes2hours($student['absence_time_2q'], "/");
		if ($tot_q2 > 0){
			$tot_q2_per = round((($student['absence_time_2q'] / $tot_q2) * 100), 2)."%";
		}
		else {
			$tot_q2_per = "0%";
		}
	}
	else{
		$st_2q_absence = "--";
		$tot_q2_per = "--";
	}
	
	if($tot_hours > 0){
		$tot_per = round((($student['absence_time'] / $tot_hours) * 100), 2);
		if($tot_per > 0){
			$tot_per .= "%";
		}
		else {
			$tot_per = "/";
		}
	}
	if($tot_q1 > 0){	
		$tot_q1_per = round((($student['absence_time_1q'] / $tot_q1) * 100), 2);
		if($tot_q1_per > 0){
			$tot_q1_per .= "%";
		}
		else {
			$tot_q1_per = "/";
		}
	}	
	
?>
<tr>
	<td style="width: 40%; padding-left: 8px; font-weight: bold; "><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?><span style="font-weight: normal; " ><?php print $student['cognome']." ".$student['nome']?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_per > 25) print("attention _bold") ?>"><?php print $st_absence ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_per > 25) print("attention _bold") ?>"><?php print $tot_per ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q1_per > 25) print("attention _bold") ?>"><?php print $st_1q_absence ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q1_per > 25) print("attention _bold") ?>"><?php print $tot_q1_per ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q2_per > 25) print("attention _bold") ?>"><?php print $st_2q_absence ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q2_per > 25) print("attention _bold") ?>"><?php print $tot_q2_per ?></span></td>	
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="8" style="text-align: right; font-weight: bold; margin-right: 30px">&nbsp;
		<input type="hidden" name="id_materia" value="<?php print $idm ?>" />
		<input type="hidden" name="materia" value="<?php print $_mat ?>" />
	</td>
</tr>
</tfoot>
</table>
</form>
</div>
<?php include "../footer.php" ?>
</body>
</html>
