<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
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
		<div class="welcome">
			<p id="w_head">Supplenze in corso</p>
			<p class="w_text" style="width: 350px">
				<?php
				$max = 3;
				$tot = count($supplenze_in_corso);
				$index = 0;
				foreach ($supplenze_in_corso as $k => $sup) {
					if ($index > 2) break;
				?>
				<a href="supplenza.php?id=<?php echo $k ?>" style="text-decoration: none"><?php echo $sup['tit']." => ".$sup['sup'] ?> (<?php echo implode(", ", $sup['classi'])  ?>)</a>
				<?php
					$index++;
				}
				if ($tot > $max) {
					$link_label = "Vedi tutte";
				}
				else {
					$link_label = "Gestisci";
				}
				?>
				<p>
					<a href="elenco_supplenze.php?status=open"><?php echo $link_label ?></a>
				</p>
			</p>
		</div>
		<div class="welcome">
			<p id="w_head">Supplenze concluse</p>
			<p class="w_text" style="width: 350px">
				<?php
				$max = 3;
				$tot = count($supplenze_concluse);
				$index = 0;
				foreach ($supplenze_concluse as $k => $sup) {
					if ($index > 2) break;
					?>
					<a href="supplenza.php?id=<?php echo $k ?>" style="text-decoration: none"><?php echo $sup['tit']." => ".$sup['sup'] ?> (<?php echo implode(", ", $sup['classi'])  ?>)</a><br/>
					<?php
					$index++;
				}
				if ($tot > $max) {
					$link_label = "Vedi tutte";
				}
				else {
					$link_label = "Gestisci";
				}
				?>
				<p>
					<a href="elenco_supplenze.php?status=closed"><?php echo $link_label ?></a>
				</p>
			</p>
		</div>
		<div class="welcome">
			<p id="w_head">Nuove supplenze</p>
			<p class="w_text" style="width: 350px">
				<a href="supplenza.php?id=0">Nuova supplenza</a>
				<p><a href="supplente.php?id=0">Nuovo supplente</a></p>
			</p>
		</div>
	</div>
	<p class="spacer"></p>
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
