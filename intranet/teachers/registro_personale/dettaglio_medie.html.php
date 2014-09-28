<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Registro di classe</title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div class="group_head">
		Riepilogo medie generali <?php print $label ?>
	</div>
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php echo ($num_colonne - 1) ?>" style="font-weight: bold; text-align: center">Quadro riassuntivo della classe</td>
</tr>
<tr class="title_tr">
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
	if(!isset($al['media'])){
		$al['media'] = 0;
	}
?>
<tr style="border-bottom: 1px solid #CCC">
	<td style="width: <?php print $first_column_width ?>%; padding-left: 8px; font-weight:normal;">
		<?php if($idx < 10) print "&nbsp;&nbsp;"; ?><?php echo $idx.". " ?>
		<span style="font-weight: normal"><?php print $al['cognome']." ".substr($al['nome'], 0, 1) ?> (</span><span class="<?php if(isset($al['media']) && ($al['media'] < 6 && $al['media'] > 0)) print("attention") ?> _bold"><?php echo $al['media'] ?></span>)
	</td>
	<?php 
	reset($materie);
	foreach ($materie as $materia){
		if (isset($al['voti'][$materia['id_materia']])){
			$avg = $al['voti'][$materia['id_materia']];
			if ($materia['id_materia'] == 26 || $materia['id_materia'] == 30) {
				$voti_rel = RBUtilities::getReligionGrades();
				$avg = $voti_rel[RBUtilities::convertReligionGrade($avg)];
			}
		}
		else {
			$avg = 0;
		}
	?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold;"><span class="<?php if($avg < 6 && $avg > 0) print("attention") ?>"><?php echo $avg ?></span></td>
<?php
	}
	$idx++;
	echo "</tr>";
}
?>
<tr class="riepilogo">
	<td style="width: <?php print $first_column_width ?>%; padding-left: 8px; font-weight:bold;">Media classe</td>
<?php 
reset($materie);
foreach ($materie as $materia){
	$sel_voti = "SELECT ROUND(AVG(voto), 2) FROM rb_voti, rb_alunni WHERE alunno = id_alunno AND id_classe = ".$_SESSION['__classe__']->get_ID()." AND materia = ".$materia['id_materia']." AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ";
	try{
		$class_avg = $db->executeCount($sel_voti);
		if ($materia['id_materia'] == 26 || $materia['id_materia'] == 30) {
			$voti_rel = RBUtilities::getReligionGrades();
			$class_avg = $voti_rel[RBUtilities::convertReligionGrade($class_avg)];
		}
	} catch (MySQLException $ex){
		$ex->redirect();
	}
?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold"><span class="<?php if($class_avg < 6 && $class_avg > 0) print("attention") ?>"><?php echo $class_avg ?></span></td>
<?php 
}
?>
</tr>
</tbody>
<tfoot>
<tr>
	<td colspan="<?php echo $num_colonne ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php echo $num_colonne ?>" style="text-align: center; height: 40px">
		<a href="dettaglio_medie.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
		</a>
		<a href="dettaglio_medie.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
		</a>
		<a href="dettaglio_medie.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
		</a>
		<!-- <a href="dettaglio_medie.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="dettaglio_medie.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="dettaglio_medie.php?q=0">Totale</a> -->
	</td>
</tr>
</tfoot>
</table>
</div>
<?php include "../footer.php" ?>
</body>
</html>
