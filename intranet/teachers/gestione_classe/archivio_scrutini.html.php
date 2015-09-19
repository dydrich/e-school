<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: archivio scrutini</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
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

		$(function() {
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
	<style>
		tbody td:nth-of-type(4n+2), tbody td:nth-of-type(4n+3) {
			background-color: rgba(236, 239, 241, .7)
		}
	</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main" style="clear: both; ">
	<?php if (isset($_REQUEST['y']) && $anno_corso == 3): ?>
		<div style="top: -20px; margin-left: 35px; margin-bottom: -10px" class="rb_button">
			<a href="archivio_scrutini.php">
				<img src="../../../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
	<?php endif; ?>
	<table class="registro">
		<thead>
		<tr class="head_tr_no_bg">
			<td style="text-align: center"><span id="ingresso" style=""><?php print $_SESSION['__classe__']->to_string() ?></span></td>
			<td colspan="<?php print ($num_materie * 2) ?>" style="text-align: center">
				Archivio scrutini a. s. <?php echo $anni_corso_classe[$index]['descrizione'] ?> - classe <?php echo $anno_sel.$sezione ?>
			</td>
		</tr>
		<tr class="title_tr">
			<td rowspan="2" style="width: <?php print $first_column_width ?>%; font-weight: bold; padding-left: 8px">Alunno</td>
			<?php
			$num_alunni = $res_alunni->num_rows;
			$tot_materie = array();
			foreach ($materie as $materia) {
				if(!isset($tot_materie[$materia['id_materia']])){
					$tot_materie[$materia['id_materia']] = array();
					$tot_materie[$materia['id_materia']]['voto1q'] = 0;
					$tot_materie[$materia['id_materia']]['voto2q'] = 0;
					$tot_materie[$materia['id_materia']]['num_al'] = $num_alunni;
				}
				?>
				<td colspan="2" style="width: <?php print $column_width * 2 ?>%; text-align: center; font-weight: bold"><?php print $materia['materia'] ?></td>
			<?php
			}
			?>
		</tr>
		<tr class="title_tr">
			<?php
			for($i = 0; $i < count($materie); $i++){
				?>
				<td style="width: <?php print $column_width ?>%; text-align: center; font-weight: normal">1Q</td>
				<td style="width: <?php print $column_width ?>%; text-align: center; font-weight: normal">2Q</td>
			<?php
			}
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		$idx = 0;
		foreach ($alunni as $alunno) {
			?>
			<tr>
				<td style="width: <?php echo $first_column_width ?>%;  padding-left: 8px; font-weight: bold"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?><span style="font-weight: normal"><?php echo $alunno['alunno'] ?></span></td>
				<?php
				reset($materie);
				foreach ($materie as $materia) {
					?>
					<td style="width: <?php print $column_width ?>%; text-align: center; font-weight: normal"><span class="<?php if($alunno['voti'][$materia['id_materia']]['1q'] < 6 && $alunno['voti'][$materia['id_materia']]['1q'] > 0) print("attention") ?>"><?php echo $alunno['voti'][$materia['id_materia']]['1q'] ?></span></td>
					<td style="width: <?php print $column_width ?>%; text-align: center; font-weight: bold"><span class="<?php if($alunno['voti'][$materia['id_materia']]['2q'] < 6 && $alunno['voti'][$materia['id_materia']]['2q'] > 0) print("attention") ?>"><?php echo $alunno['voti'][$materia['id_materia']]['2q'] ?></span></td>
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
		</tfoot>
	</table>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu separator"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
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
