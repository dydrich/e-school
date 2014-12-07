<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Gestione classe</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
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
	<?php
	if($vacance){
	?>
	<div id="welcome" style="">
		<p id="w_head" style="background: url(../../../images/70.png) no-repeat;">BUONE VACANZE</p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Ci rivediamo a settembre</p>
	</div>
	<?php 
	}
	else {
	?>
	<div id="welcome" style="margin-bottom: 40px">
		<p id="w_head" class="accent_decoration" style="background-image: none; padding-bottom: 2px">
			<i class="fa fa-calendar normal" style="position: relative; left: -30px; font-size: 1.2em"></i>
			<span style="margin-left: -15px"><?php echo $tod ?></span>
		</p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Compiti</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_today_hw->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessun compito &egrave; stato ancora assegnato</li>
		<?php 
		}
		else{
			while($hw = $res_today_hw->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $hw['mat'].":: ".$hw['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Attivit&agrave;</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_today_act->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessuna attivit&agrave; prevista</li>
		<?php 
		}
		else{
			while($act = $res_today_act->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $act['mat'].":: ".$act['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
	</div>
	<div id="welcome" style="margin-bottom: 40px">
		<p id="w_head" class="accent_decoration" style="background-image: none; padding-bottom: 2px">
			<i class="fa fa-calendar normal" style="position: relative; left: -30px; font-size: 1.2em"></i>
			<span style="margin-left: -15px"><?php echo $tom ?></span>
		</p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Compiti</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_tomorrow_hw->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessun compito &egrave; stato ancora assegnato</li>
		<?php 
		}
		else{
			while($hw = $res_tomorrow_hw->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $hw['mat'].":: ".$hw['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Attivit&agrave;</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_tomorrow_act->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessuna attivit&agrave; prevista</li>
		<?php 
		}
		else{
			while($act = $res_tomorrow_act->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $act['mat'].":: ".$act['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
	</div>
	<div id="welcome" style="">
		<p id="w_head" class="accent_decoration" style="background-image: none; padding-bottom: 2px">
			<i class="fa fa-calendar normal" style="position: relative; left: -30px; font-size: 1.2em"></i>
			<span style="margin-left: -15px"><?php echo $post_tm ?></span>
		</p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Compiti</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_day3_hw->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessun compito &egrave; stato ancora assegnato</li>
		<?php 
		}
		else{
			while($hw = $res_day3_hw->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $hw['mat'].":: ".$hw['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Attivit&agrave;</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_day3_act->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessuna attivit&agrave; prevista</li>
		<?php 
		}
		else{
			while($act = $res_day3_act->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $act['mat'].":: ".$act['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
	</div>
	<?php 
	}
	?>
</div> 
<p class="spacer"></p>
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
