<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: riepilogo classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var _show = function(e, off) {
			if ($('#other_drawer').is(":visible")) {
				$('#other_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#other_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#other_drawer').show('slide', 300);
			return true;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<table class="registro">
		<thead>
		<tr class="head_tr_no_bg">
			<td style="text-align: center; "><span id="ingresso" style=""><?php print $_SESSION['__classe__']->to_string() ?></span></td>
			<td colspan="<?php echo ($num_colonne - 1) ?>" style="text-align: center">Quadro riassuntivo dell'alunno</td>
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
				<a href="scheda_alunno.php?stid=<?php echo $al['id_alunno'] ?>" style="font-weight: normal"><?php print $al['cognome']." ".substr($al['nome'], 0, 1) ?> (</a><span class="<?php if(isset($al['media']) && ($al['media'] < 6 && $al['media'] > 0)) print("attention _bold") ?>"><?php echo $al['media'] ?></span>)
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
		</tbody>
		<tfoot>
		<tr>
			<td colspan="<?php echo $num_colonne ?>" style="height: 15px"></td>
		</tr>
		<tr class="nav_tr">
			<td colspan="<?php echo $num_colonne ?>" style="text-align: center; height: 40px">
				<a href="medie_voto.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />1 Quadrimestre
				</a>
				<a href="medie_voto.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />2 Quadrimestre
				</a>
				<a href="medie_voto.php?q=0" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />Totale
				</a>
				<!-- <a href="dettaglio_medie.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="dettaglio_medie.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="dettaglio_medie.php?q=0">Totale</a> -->
			</td>
		</tr>
		</tfoot>
	</table>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>

		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu"><a href="scrutini.php?q=1"><img src="../../../images/74.png" style="margin-right: 10px; position: relative; top: 5%" />Scrutini</a></div>
		<div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
