<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			}).css({
				cursor: "pointer"
			});
		});

		var _show = function(e, off) {
			if ($('#classeslist_drawer').is(":visible")) {
				$('#classeslist_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#classeslist_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#classeslist_drawer').show('slide', 300);
			return true;
		};
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "class_working.php" ?>
	</div>
	<div id="left_col">
		<div class="mdtabs">
			<div class="mdtab<?php if (!isset($_REQUEST['all'])) echo " mdselected_tab" ?>">
				<a href="elenco_compiti.php"><span>Personali</span></a>
			</div>
			<div class="mdtab<?php if (isset($_REQUEST['all'])) echo " mdselected_tab" ?>">
				<a href="elenco_compiti.php?all=1"><span>Tutti</span></a>
			</div>
		</div>
		<div style="position: absolute; top: 92px; margin-left: 625px; margin-bottom: 5px" class="rb_button">
			<a href="dettaglio_compito.php?t=0">
				<img src="../../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
	<?php
	if($res_act->num_rows < 1){
	?>
		<div id="nodata" style="width: 90%; margin: 25px auto 0 auto; font-size: 1.1em; font-weight: bold; text-align: center">Nessun compito assegnato</div>

	<?php
	}
	else{
	?>
	<div class="card_container" style="margin-top: 15px; width: 80%; border: 1px solid #DDDDDD">
	<?php
		$idx = 1;
		$bc = "";
		$data = "";
		while($dt = $res_dates->fetch_assoc()){
			if($idx > 1) {
				//print('<tr><td colspan="5" style="text-align: center; border-width: 0px 1px 1px 1px; border-style: solid; font-weight: bold; font-size: 12px;height: 10px; border-color: #B0BEC5;"></td></tr>');
			}
			$sel_hw = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio = '".$dt['data_inizio']."' AND rb_impegni.tipo = 2 $teacher ORDER BY data_inizio";
			//print $sel_hw;
			$res_hw = $db->execute($sel_hw);
			$rows = $res_hw->num_rows;
			$ct = 1;
			list($di, $oi) = explode(" ", $dt['data_inizio']);
			setlocale(LC_ALL, "it_IT.utf8");
			$giorno_str = strftime("%A %d %B %Y", strtotime($di));
	?>
		<p class="material_title material_dark_bg" style="float: none;"><span><?php echo $giorno_str ?></span></p>
	<?php
			while($hw = $res_hw->fetch_assoc()){
				$bc = "";
				list($da, $oa) = explode(" ", $hw['data_assegnazione']);
				$mod = 1;
				if($_SESSION['__user__']->getUid() != $hw['docente']) {
					$mod = 0;
				}
	?>
			<?php if ($mod): ?>
			<a href="dettaglio_compito.php?t=<?php echo $hw['id_impegno'] ?>">
			<?php endif; ?>
			<div class="card" style="width: 94%; margin-left: 1%">
				<div class="card_title<?php if (!$mod) echo " _italic" ?>" style="padding-left: 0; width: 100%">
					<?php echo $hw['mat'] ?>
				</div>
				<div class="card_varcontent <?php if ($mod) echo " normal"; else echo " _italic" ?>"><?php echo $hw['descrizione'] ?></div>
			</div>
			<?php if ($mod): ?>
			</a>
			<?php endif; ?>

	<?php
				$ct++;
				$idx++;
			}
		}
	?>
		</div>
	<?php
	}
	?>
	</div>
	<p class="spacer"></p>
</div>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
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
<div id="classeslist_drawer" class="drawer" style="height: <?php echo (36 * (count($_SESSION['__user__']->getClasses()) - 1)) ?>px; display: none; position: absolute">
	<?php
	foreach ($_SESSION['__user__']->getClasses() as $cl) {
		if ($cl['id_classe'] != $_SESSION['__classe__']->get_ID()) {
			?>
			<div class="drawer_link ">
				<a href="<?php echo getFileName() ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>">
					<img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
					Classe <?php echo $cl['classe'] ?>
				</a>
			</div>
		<?php
		}
	}
	?>
</div>
</body>
</html>
