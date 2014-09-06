<!DOCTYPE html>
<html>
<head>
<title>Riepilogo medie voto</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">

</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Riepilogo medie generali <?php print $label ?>
	</div>
<table class="manager_table" style="width: 100%; margin-top: 10px">
<thead>
<tr style="height: 25px; background-color: rgba(30, 67, 137, .3);">
	<td style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php echo ($num_colonne - 1) ?>" style="font-weight: bold; text-align: center">Quadro riassuntivo della classe</td>
</tr>
<tr class="manager_row_small">
	<td style="width: <?php echo $first_column_width ?>%; font-weight: bold; padding-left: 12px">Alunno</td>
	<?php 
	foreach ($materie as $materia){
	?>
	<td <?php if($materia['id_materia'] == 1111) print ("rowspan='2'") ?> style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold"><?php echo strtoupper(substr($materia['materia'], 0, 3)) ?></td>
	<?php 
	}
	?>
</tr>
</thead>
<tbody>
<?php 
$idx = 1;
foreach ($alunni as $al){
	$student_sum = 0;
	$num_materie = $res_materie->num_rows;
?>
<tr class="manager_row_small">
	<td style="width: <?php print $first_column ?>%; padding-left: 8px; font-weight:normal;">
		<?php if($idx < 10) print "&nbsp;&nbsp;"; ?><?php echo $idx.". " ?>
		<span style="font-weight: normal"><?php print $al['cognome']." ".substr($al['nome'], 0, 1) ?> (</span><span class="<?php if(isset($al['media']) && $al['media'] < 6 && $al['media'] > 0) print("attention") ?> _bold"><?php if(isset($al['media'])) echo $al['media'] ?></span>)
	</td>
	<?php 
	reset($materie);
	foreach ($materie as $materia){
		if (isset($al['voti'][$materia['id_materia']])) {
			$avg = $al['voti'][$materia['id_materia']];
		}
	?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold;"><span class="<?php if($avg < 6 && $avg > 0) print("attention") ?>"><?php echo $avg ?></span></td>
<?php
	}
	$idx++;
	echo "</tr>";
}
?>
<tr style="background-color: rgba(30, 67, 137, .1); height: 30px">
	<td style="width: <?php print $first_column ?>%; padding-left: 8px; font-weight:bold; border-bottom: rgba(30, 67, 137, .3)">Media classe</td>
<?php 
reset($materie);
foreach ($materie as $materia){
	$sel_voti = "SELECT ROUND(AVG(voto), 2) FROM rb_voti, rb_alunni WHERE alunno = id_alunno AND id_classe = ".$_SESSION['__classe__']->get_ID()." AND materia = ".$materia['id_materia']." AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ";
	try{
		$class_avg = $db->executeCount($sel_voti);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; border-bottom: rgba(30, 67, 137, .3); font-weight: bold;<?php print $background ?>"><span class="<?php if($class_avg < 6 && $class_avg > 0) print("attention") ?>"><?php echo $class_avg ?></span></td>
<?php 
}
?>
</tr>
</tbody>
<tfoot>
<tr style="border-top: 1px solid rgba(30, 67, 137, .3)">
	<td colspan="<?php echo $num_colonne ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php echo $num_colonne ?>" style="text-align: center; height: 40px">
		<a href="medie_classe.php?cls=<?php echo $_REQUEST['cls'] ?>&q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />1 Quadrimestre
		</a>
		<a href="medie_classe.php?cls=<?php echo $_REQUEST['cls'] ?>&q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />2 Quadrimestre
		</a>
		<a href="medie_classe.php?cls=<?php echo $_REQUEST['cls'] ?>&q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />Totale
		</a>
	</td>
</tr>
<tr>
	<td colspan="<?php echo $num_colonne ?>" style="height: 15px"></td>
</tr>
</tfoot>
</table>
</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
