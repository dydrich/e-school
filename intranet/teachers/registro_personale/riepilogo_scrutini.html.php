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

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="<?php print (($num_subject*2) + 1) ?>" style="text-align: center; font-weight: bold">Riepilogo personale scrutini - <?php print $label ?>
	<?php 
	if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) || ($_SESSION['__user__']->isAdministrator()) || ($_SESSION['__user__']->getUsername() == "rbachis")){
	?>
	<a href="scrutini_classe.php?q=<?php echo $q ?>" style="font-weight: normal; float: right; margin-right: 10px">Dettaglio classe</a>
	<?php 
	}
	?>
	</td>
</tr>
<tr class="head_tr_no_bg">
	<td style="text-align: center"><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php print ($num_subject * 2) ?>" style="font-weight: bold; text-align: center">Quadro riassuntivo</td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: <?php print $first_column ?>%; font-weight: bold; padding-left: 8px">Alunno</td>
	<?php
	$num_alunni = $res_alunni->num_rows;
	$tot_materie = array();
	foreach ($_SESSION['__subjects__'] as $materia) {
		if(!isset($tot_materie[$materia['id']])){
			$tot_materie[$materia['id']] = array();
			$tot_materie[$materia['id']]['voto'] = 0;
			$tot_materie[$materia['id']]['abs'] = 0;
			$tot_materie[$materia['id']]['num_al'] = $num_alunni;
		}
	?>
	<td colspan="2" style="width: <?php print $other_column * 2 ?>%; text-align: center; font-weight: bold"><a href="scrutini.php?q=<?php echo $q ?>&subject=<?php echo $materia['id'] ?>" style=""><?php print $materia['mat'] ?></a></td>
	<?php 
	}
	?>
</tr>
<tr class="title_tr">
	<?php 
	for($i = 0; $i < count($_SESSION['__subjects__']); $i++){
	?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weght: bold">Voto</td>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weght: bold">Assenze</td>
	<?php 
	}
	?>
</tr>
</thead>
<tbody>
<?php
$idx = 0;
while($al = $res_alunni->fetch_assoc()){
?>
<tr>
	<td style="width: <?php print $first_column ?>%;  padding-left: 8px; font-weight: bold"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?><span style="font-weight: normal"><?php print $al['cognome']." ".$al['nome']?></span></td>
<?php 
	reset($_SESSION['__subjects__']);
	foreach ($_SESSION['__subjects__'] as $materia) {
		$sel_voti = "SELECT voto, assenze FROM rb_scrutini WHERE alunno = ".$al['id_alunno']." AND materia = ".$materia['id']." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = $q";
		try{
			$res_voti = $db->executeQuery($sel_voti);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		$dt = $res_voti->fetch_assoc();
		if($dt['voto'] > 0){
			$tot_materie[$materia['id']]['voto'] += $dt['voto'];
			$tot_materie[$materia['id']]['abs'] += $dt['assenze'];
		}
		else {
			--$tot_materie[$materia['id']]['num_al'];
		}
?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: bold"><span class="<?php if($dt['voto'] < 6 && $dt['voto'] > 0) print("attention") ?>"><?php echo $dt['voto'] ?></span></td>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: normal"><?php if ($ordine_scuola == 1) echo $dt['assenze']; else echo "--" ?></td>
<?php 
	}
?>	
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr style="height: 30px; background-color: rgba(30, 67, 137, .4)">
<td style="width: <?php print $first_column ?>%; padding-left: 8px; font-weight: bold">Media classe</td>
	<?php
	
	reset($_SESSION['__subjects__']);
	foreach ($_SESSION['__subjects__'] as $materia) {
		$avg = "--";
		$abs_avg = "--";
		if($tot_materie[$materia['id']]['num_al'] > 0){
			$avg = $tot_materie[$materia['id']]['voto'] / $tot_materie[$materia['id']]['num_al'];
			$abs_avg = round(($tot_materie[$materia['id']]['abs'] / $tot_materie[$materia['id']]['num_al']), 2);
		}
	?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: bold"><span class="<?php if($avg < 6) print("attention") ?>"><?php echo round($avg, 2) ?></span></td>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: normal"><?php if ($ordine_scuola == 1) echo $abs_avg; else echo "--" ?></td>
	<?php 
	}
	?>
</tr>
<tr>
	<td colspan="<?php print (($num_subject*2) + 1) ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php print (($num_subject*2) + 1) ?>" style="text-align: center; height: 40px">
		<a href="riepilogo_scrutini.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
		</a>
		<a href="riepilogo_scrutini.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
		</a>
	</td>
</tr>
</tfoot>
</table>
</div>
<?php include "../footer.php" ?>
</body>
</html>
