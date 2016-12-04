<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Area docenti: colloqui prenotati</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript" src="../../../js/md5-min.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});

	</script>
	<style>
		div.welcome {
			padding-bottom: 15px;
		}
	</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../working.php" ?>
	</div>
	<div id="left_col">
		<?php
		foreach ($data as $item) {
			$giorno_str = strftime("%A %d %B", strtotime($item['data']));
		?>
		<div class="welcome" style="padding-top: 0">
			<p id="s_head" style="margin-bottom: 0; background-image: none">
				<i class="fa fa-calendar" style="position: relative; left: -30px; font-size: 1.4em"></i>
				<span style="position: relative; left: -20px"><?php echo $giorno_str ?></span>
			</p>
			<?php
			if(is_array($item['reservations']) && count($item['reservations']) > 0) {
				foreach ($item['reservations'] as $reservation) {
					?>
			<p><?php echo $reservation['nome']." ".$reservation['cognome'] ?></p>
			<?php
				}
			}
			else {
				?>
			<p>Nessuna prenotazione</p>
			<?php
			}
			?>
		</div>
		<?php
		}
		?>

	</div>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<?php if ($_SESSION['__user__']->hasConnectedAccounts()) {
			$acc = $_SESSION['__user__']->getConnectedAccounts();
			foreach ($acc as $ca) {
				$mat = $db->executeCount("SELECT rb_materie.materia FROM rb_materie, rb_docenti WHERE rb_docenti.materia = id_materia AND id_docente = $ca");
				?>
				<div class="drawer_link">
					<a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=sudo&area=3&uid=<?php echo $ca ?>">
						<img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
						Cambia utente (<?php echo $mat ?>)</a>
				</div>
				<?php
			}
		}
		?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="adm_pwd" style="display: none">
	<p>
		<label for="pass" class="material_label">Inserisci la password</label>
		<input type="password" class="material_input" id="pass" name="pass" style="width: 200px" />
	</p>
	<p style="margin-top: 45px; text-align: right">
		<a href="#" id="su_go" class="material_link">SuperUser</a>
	</p>
</div>
</body>
</html>
