<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Riepilogo medie voto</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
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
<table class="manager_table" style="width: 99%; margin: 10px auto 0 auto">
<thead>
<tr style="height: 35px">
	<td colspan="<?php echo $num_colonne ?>" style="font-weight: normal; text-align: left">
		<div class="rowcard" style="width: 75%; height: 20px; margin-left: 20px"><span id="ingresso" style="font-weight: normal; "><?php print $_SESSION['__classe__']->to_string() ?></span>::Quadro riassuntivo della classe</div>
	</td>
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
<tr class="bottom_decoration">
	<td style="width: <?php print $first_column_width ?>%; padding-left: 8px; font-weight:normal;">
		<?php if($idx < 10) print "&nbsp;&nbsp;"; ?><?php echo $idx.". " ?>
		<span style="font-weight: normal"><?php print $al['cognome']." ".substr($al['nome'], 0, 1) ?> (</span><span class="<?php if(isset($al['media']) && $al['media'] < 6 && $al['media'] > 0) print("attention") ?> _bold"><?php if(isset($al['media'])) echo $al['media'] ?></span>)
	</td>
	<?php 
	reset($materie);
	foreach ($materie as $materia){
		if (isset($al['voti'][$materia['id_materia']])) {
			$avg = $al['voti'][$materia['id_materia']];
		}
		else {
			$avg = "";
		}
	?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: normal;"><span class="<?php if($avg != "" && $avg < 6 && $avg > 0) print("attention") ?>"><?php echo $avg ?></span></td>
<?php
	}
	$idx++;
	echo "</tr>";
}
?>
</tbody>
<tfoot>
<tr class="bottom_decoration" style="height: 30px">
	<td style="width: <?php print $first_column_width ?>%; padding-left: 8px; font-weight:bold">Media classe</td>
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
	<td style="width: <?php echo $column_width ?>%; text-align: center; border-bottom: rgba(30, 67, 137, .3); font-weight: bold"><span class="<?php if($class_avg < 6 && $class_avg > 0) print("attention") ?>"><?php echo $class_avg ?></span></td>
<?php 
}
?>
</tr>
</tfoot>
</table>
	<div class="navigate" style="margin-bottom: 30px">
	<a href="medie_classe.php?cls=<?php echo $_REQUEST['cls'] ?>&q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
		<img style="margin-right: 5px; position: relative; top: 2px" src="../../images/24.png" />1 Quadrimestre
	</a>
	<a href="medie_classe.php?cls=<?php echo $_REQUEST['cls'] ?>&q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
		<img style="margin-right: 5px; position: relative; top: 2px" src="../../images/24.png" />2 Quadrimestre
	</a>
	<a href="medie_classe.php?cls=<?php echo $_REQUEST['cls'] ?>&q=0" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
		<img style="margin-right: 5px; position: relative; top: 2px" src="../../images/24.png" />Totale
	</a>
	</div>
</div>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
