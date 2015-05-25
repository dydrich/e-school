<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var firma = function(){

		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.goto_reg').click(function(event){
				event.stopPropagation();
				var dt = $(this).attr("data-dt");
				document.location.href = "registro_classe.php?data="+dt;
			});
			$('.goto_sign').click(function(event){
				var dt = $(this).attr("data-dt");
				var idreg = $(this).attr("data-idreg");
				document.location.href = "sign.php?id_reg="+idreg+"&data="+dt;
			})
			$('td[rowspan]').addClass('hasRowSpan');
		});

	</script>
	<style>
		.hasRowSpan {
			background-color: white;
		}
	</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<form>
		<div id="not1" class="notification"></div>
		<table class="registro">
			<thead>
				<tr class="title_tr">
					<td style="width: 7%; font-weight: bold"></td>
					<td style="width: 3%; font-weight: bold; text-align: center">Ora</td>
					<td style="width: 15%; font-weight: bold; text-align: center">Materia</td>
					<td style="width: 25%; text-align: center; font-weight: bold">Docente</td>
					<td style="width: 35%; text-align: center; font-weight: bold">Argomento</td>
					<td style="width: 15%; text-align: center; font-weight: bold">Assenti</td>
				</tr>
			</thead>
			<tbody>
			<?php
			for ($j = 1; $j < ($last_day + 1); $j++) {
				if ($days[$j]['mensa']) {
					$days[$j]['hours']++;
				}
				for ($i = 0; $i < $days[$j]['hours']; $i++) {
					if ($i > 5) {
						$index = $i;
					}
					else {
						$index = $i + 1;
					}
					if ($i == 5) {
						/*
						 * mensa
						 */
						?>
						<tr class="goto_sign" data-dt="<?php echo $days[$j]['date'] ?>"
						    data-idreg="<?php echo $days[$j]['id_reg'] ?>">
							<td style="width: 3%; font-weight: bold;text-align: center"><?php echo $i + 1 ?></td>
							<td colspan="3" style="font-weight: bold; padding-left: 25px; background-color: #dadada">Mensa</td>
						</tr>
					<?php
					}
					else {
						?>
						<tr class="goto_sign" data-dt="<?php echo $days[$j]['date'] ?>" data-idreg="<?php echo $days[$j]['id_reg'] ?>" style="<?php if ($i == ($days[$j]['hours'] - 1)) {echo "border-bottom: 2px solid rgba(30, 67, 137, .6)";} ?>">
							<?php
							if ($i == 0) {
								?>
								<td rowspan="<?php echo $days[$j]['hours'] ?>" style="width: 7%; font-weight: bold; padding-left: 8px"><?php echo $days[$j]["date_short_print"] ?></td>
							<?php
							}
							?>
							<td style="width: 3%; font-weight: bold;text-align: center"><?php echo $i + 1 ?></td>
							<td style="width: 15%; font-weight: bold; padding-left: 2px"><?php if (isset($days[$j]['firme'][$index]['materia'])) {echo $days[$j]['firme'][$index]['materia'];} if (isset($days[$j]['firme'][$index]['sostegno']) && count($days[$j]['firme'][$index]['sostegno']) > 0) {echo " (sos)";} ?></td>
							<td style="width: 25%; text-align: left; font-weight: normal"><?php if (isset($days[$j]['firme'][$index]['docente'])) {echo $days[$j]['firme'][$index]['docente'];} ?></td>
							<td style="width: 35%; text-align: left; font-weight: normal"><?php if (isset($days[$j]['firme'][$index]['argomento'])) {echo $days[$j]['firme'][$index]['argomento'];} ?></td>
							<?php
							if ($i == 0) {
								?>
								<td rowspan="<?php echo $days[$j]['hours'] ?>" class="goto_reg" data-dt="<?php echo $days[$j]['date'] ?>" style="width: 15%; text-align: left; font-weight: normal; vertical-align: top"><?php echo $days[$j]["assenti"] ?></td>
							<?php
							}
							?>
						</tr>
					<?php
					}
				}
			}
			?>
			<tbody>
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>
		</table>
	</form>
	<p></p>
</div>
<!-- fine menu contestuale -->
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu">
			<a href="registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro di classe</a>
		</div>
		<div class="drawer_link submenu">
			<a href="stats.php"><img src="../../../images/18.png" style="margin-right: 10px; position: relative; top: 5%"/>Statistiche</a>
		</div>
		<div class="drawer_link submenu separator">
			<a href="notes.php"><img src="../../../images/26.png" style="margin-right: 10px; position: relative; top: 5%"/>Note</a>
		</div>
		<div class="drawer_link submenu"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
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
