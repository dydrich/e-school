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
<style>
table.registro td {
	border: 0px
}
</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<table class="registro" style="width: 99%; margin: auto">
	<thead>
	<tr class="head_tr_no_bg">
		<td colspan="3" style="text-align: right">
			
		</td>
	</tr>
	<tr class="head_tr_no_bg">
		<td colspan="5" style="text-align: center; text-decoration: underline; font-weight: bold; padding-bottom: 15px; text-transform: uppercase">Elenco voti di <?php echo $fn ?>
			<a href="grades_list.php?q=<?= $q ?>&order=<?= $order ?>&stid=<?= $student ?>&field_order=data_voto<?= $_group ?>" style="float: right; font-weight: normal; "><?= $link_label ?></a>
			<?php if(!$group){ ?>
			<span style="float: right; padding-right: 10px; padding-left: 10px; font-weight: normal">|</span>
			<a href="grades_list.php?q=<?= $q ?>&order=<?= $order ?>&stid=<?= $student ?>&field_order=rb_materie.materia,data_voto<?= $_group2 ?>" style="float: right; font-weight: normal; "><?= $link_label2 ?></a>
			<?php } ?>
		</td>	
	</tr>
	<tr class="head_tr" style="border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199)">
		<td style="width: 10%; text-align: center; font-weight: bold; padding-left: 20px; vertical-align: middle">Data</td>
		<td style="width: 5%; "><img src="/rclasse/images/<?php echo $image ?>" style="margin-left: 8px" onclick="document.location.href='grades_list.php?q=<?= $q ?>&order=<?= $order_to ?>&stid=<?= $student ?>&field_order=<?= $field_order ?>&group=<?= ($group) ? $_REQUEST['group'] : 0 ?>'" /></td>
		<td style="width: 15%; text-align: center; font-weight: bold; vertical-align: middle">Voto</td>
		<td style="width: 15%; text-align: center; font-weight: bold; vertical-align: middle">Materia</td>
		<td style="width: 55%; text-align: center; font-weight: bold;">Argomento</td>
	</tr>
	<tr>
		<td colspan="5" style="border-bottom: 1px solid #cccccc; height: 15px"></td>	
	</tr>
	</thead>
	<tbody>
	<?php
	$idx = 0;
	$day = "";
	$mese = "";
	$subject = "";
	$count_per_month = 0;
	$count_per_subject = 0;
	$sum_month = $sum_subject = 0;
	$first_month = "";
	while($voto = $res_voti->fetch_assoc()){
		setlocale(LC_TIME, "it_IT");
		list($y, $m, $d) = explode("-", $voto['data_voto']);
		if($idx == 0) $first_month = $m;
		if($group){
			if($_REQUEST['group'] == 1){
				if($mese != $m){
					if($mese != "")
						$avg = round(($sum_month / $count_per_month), 2);
					$str_month = ucfirst(strftime("%B", strtotime($voto['data_voto'])));
					print("<tr><td colspan='5' style='height: 20px; vertical-align: middle; font-weight: normal; text-transform: uppercase; font-size: 13m; text-align: center; border-bottom: 1px solid #CCCCCC; background-color: rgba(211, 222, 199, 0.3); '>$str_month<span id='avg_per_month_$m' style='font-weight: normal; font-size: 12px'></span></td></tr>");
					if($mese != "")
						print("<script>$('avg_per_month_$mese').innerHTML = ' ($avg)';</script>");
					$count_per_month = $sum_month = 0;
				}
			}
			else{
				if($subject != $voto['mat']){
					if($subject != "")
						$avg = round(($sum_subject / $count_per_subject), 2);
					print("<tr><td colspan='5' style='height: 20px; vertical-align: middle; font-weight: normal; text-transform: uppercase; font-size: 13m; text-align: center; border-bottom: 1px solid #CCCCCC; background-color: rgba(211, 222, 199, 0.3); '>".$voto['mat']."<span id='avg_per_".$voto['mat']."' style='font-weight: normal; font-size: 12px'></span></td></tr>");
					if($subject != "")
						print("<script>$('avg_per_$subject').innerHTML = ' ($avg)';</script>");
					$count_per_subject = $sum_subject = 0;
				}
			}
		}
		$sum_month += $voto['voto'];
		$sum_subject += $voto['voto'];
		$count_per_month++;
		$count_per_subject++;
		$giorno_str = ($_REQUEST['group'] == 1) ? ucfirst(utf8_encode(strftime("%A %d", strtotime($voto['data_voto'])))) : ucfirst(utf8_encode(strftime("%A %d %B", strtotime($voto['data_voto']))));
		$print_day = ($day != $voto['data_voto']) ? true : false;
		if($voto['voto'] < 6)
			$color = "color: red";
		else
			$color = "";
	?>
	<tr style="border-bottom: 1px solid #cccccc">
		<td colspan="2" style="width: 25%; text-align: left; padding-left: 20px; font-weight: normal; <?= $color ?>"><?php if($print_day) print $giorno_str ?></td>
		<td style="width: 15%; text-align: center; font-weight: normal; <?= $color ?>"><?= $voto['voto'] ?></td>
		<td style="width: 15%; text-align: center; font-weight: normal; <?= $color ?>"><?= $voto['mat'] ?></td>
		<td style="width: 55%; text-align: center; font-weight: normal; <?= $color ?>"><?php print utf8_decode($voto['descrizione']) ?></td>
	</tr>
	<?php
		$day = $voto['data_voto'];
		$mese = $m;
		$subject = $voto['mat'];
		$idx++;
	}
	$avg = round(($sum_month / $count_per_month), 2);
	print("<script>$('avg_per_month_$mese').innerHTML = ' ($avg)';</script>");
	$avg = round(($sum_subject / $count_per_subject), 2);
	print("<script>$('avg_per_$subject').innerHTML = ' ($avg)';</script>");
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="5" style="height: 35px; "></td>
	</tr>
	<tr style="text-align: center; border-width: 1px 0 1px 0; border-style: solid; border-color: #CCCCCC; height: 40px">
		<td colspan="5" style="height: 35px">
			<a href="grades_list.php?q=1&order=<?= $order_to ?>&stid=<?= $student ?>&field_order=<?= $field_order ?>&group=<?= ($group) ? 1 : 0 ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="grades_list.php?q=2&order=<?= $order_to ?>&stid=<?= $student ?>&field_order=<?= $field_order ?>&group=<?= ($group) ? 1 : 0 ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
			</a>
			<a href="grades_list.php?q=0&order=<?= $order_to ?>&stid=<?= $student ?>&field_order=<?= $field_order ?>&group=<?= ($group) ? 1 : 0 ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
			</a>
			<!-- <a href="grades_list.php?q=1&order=<?= $order_to ?>&stid=<?= $student ?>&field_order=<?= $field_order ?>&group=<?= ($group) ? 1 : 0 ?>">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="grades_list.php?q=2&order=<?= $order_to ?>&stid=<?= $student ?>&field_order=<?= $field_order ?>&group=<?= ($group) ? 1 : 0 ?>">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="grades_list.php?q=0&order=<?= $order_to ?>&stid=<?= $student ?>&field_order=<?= $field_order ?>&group=<?= ($group) ? 1 : 0 ?>">Totale</a> -->
		</td>
	</tr>
	<tr style="text-align: right; height: 40px; border-top: 1px solid #CCCCCC">
		<td colspan="5" style="padding-right: 30px">			
			<a href="summary.php?q=<?php echo $q ?>" style="text-transform: uppercase; text-decoration: none" onclick="new_test()"><img src="../../../images/back.png" style="margin-right: 5px; position: relative; top: 5px" />Torna al riepilogo</a>
		</td>
	</tr>
	</tfoot>
</table>
</div> 
<?php include "../footer.php" ?>
</body> 
</html>