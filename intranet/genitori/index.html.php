<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
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
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "class_working.php" ?>
	</div>
	<div id="left_col">
		<div class="welcome">
			<p id="w_head">Bentornato <?php echo $_SESSION['__user__']->getFullName() ?></p>
			<p class="w_text">
				Sei entrato nell'area riservata ai genitori della intranet della <?php echo $_SESSION['__config__']['intestazione_scuola'] ?>.<br />
				In quest'area avrai accesso a varie informazioni riguardanti i tuoi figli e potrai comunicare con il personale della scuola, sia docente che non docente.<br />
				<p class="attention _bold">Ti preghiamo di scaricare il <a href="http://www.istitutoiglesiasserraperdosa.it/rclasse/modules/documents/download_manager.php?id=1146&doc=document">manuale introduttivo</a> alla nuova versione del Registro elettronico.</p>
			</p>
		</div>
		<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_msg.php'; ?>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if (count($_SESSION['__sons__']) > 1): ?>
		<div class="drawer_link separator">
			<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
		</div>
		<?php endif; ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/communication/load_module.php?module=com&area=genitori"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php
if (count($_SESSION['__sons__']) > 1){
	$height = 36 * (count($_SESSION['__sons__']));
?>
<div id="other_drawer" class="drawer" style="height: <?php echo  $height ?>px; display: none; position: absolute">
	<?php
	$indice = 1;
	reset($_SESSION['__sons__']);
	while(list($key, $val) = each($_SESSION['__sons__'])){
	$cl = "";
	if ($key == $_SESSION['__current_son__']) {
		$cl = " _bold";
	}
	?>
	<div class="drawer_link">
		<a href="<?php print $page ?>?son=<?php print $key ?>" clas="<?php echo $cl ?>"><?php print $val[0] ?></a>
	</div>
<?php
	}
?>
</div>
<?php
}
?>
</body>
</html>
