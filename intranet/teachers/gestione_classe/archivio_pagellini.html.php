<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: riepilogo classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
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
			$('.tab_link').on('click', function (event) {
				$idp = $(this).data('idp');
				$m = $(this).data('m');
				document.location.href = 'archivio_pagellini.php?idp='+$idp+'&m='+$m;
			});
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main" style="clear: both; ">
	<?php
	if (count($active) > 1) {
	?>
	<div class="mdtabs">
		<?php
		foreach ($active as $ms) {
			?>
			<div class="mdtab<?php if ($idp == $ms['id_pagellino']) echo " mdselected_tab" ?>">
				<a href="#" class="tab_link" data-idp="<?php echo $ms['id_pagellino'] ?>" data-m="<?php echo $ms['mese'] ?>"><span><?php echo $months[$ms['mese']] ?></span></a>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	}

	?>
	<?php
	if (count($active) < 1) {
		?>
    <div style="margin-left: 40px; margin-bottom: 50px; font-size: 1.2em" class="_bold">
        Nessuna segnalazione presente
    </div>
		<?php
	}
	else {
		?>
		<table class="registro">
			<thead>
			<tr class="head_tr_no_bg">
				<td style="text-align: right; "><span id="ingresso"
													  style=""><?php print $_SESSION['__classe__']->to_string() ?></span>
				</td>
				<td colspan="<?php echo($num_colonne - 1) ?>" style="text-align: center">Quadro riassuntivo della
					classe
				</td>
			</tr>
			<tr class="title_tr">
				<td style="width: <?php echo $first_column_width ?>%; font-weight: bold; padding-left: 12px">Alunno</td>
				<?php
				foreach ($materie as $materia) {
					?>
					<td <?php if ($materia['id_materia'] == 1111) print ("rowspan='2'") ?>
						style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold"><?php echo strtoupper(substr($materia['materia'], 0, 3)) ?></td>
					<?php
				}
				?>
			</tr>
			</thead>
			<tbody>
			<?php
			$idx = 1;
			foreach ($alunni as $al){
			$esonerato = 0;
			if (in_array($al['id_alunno'], $esonerati)) {
				$esonerato = 1;
			}
			?>
			<tr style="border-bottom: 1px solid #CCC">
				<td style="width: <?php print $first_column_width ?>%; padding-left: 8px; font-weight:normal;">
					<?php if ($idx < 10) print "&nbsp;&nbsp;"; ?><?php echo $idx . ". " ?>
					<span><?php print $al['cognome'] . " " . $al['nome'] ?>
						(<?php echo count($al['ins']) . " su " . count($materie) ?>)</span>
				</td>
				<?php
				reset($materie);
				foreach ($materie as $idm => $materia) {
					$val = "Suff";
					if (in_array($idm, $al['ins'])) {
						$val = "Insuff";
					}
					?>
					<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold;<?php if (($materia['id_materia'] == 26 || $materia['id_materia'] == 30) && $esonerato == 1) echo "background-color: #DDDDDD" ?>">
						<span class="<?php if ($val == 'Insuff') print("attention") ?>"><?php echo $val ?></span></td>
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
			</tfoot>
		</table>
		<?php
	}
	?>
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
