<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $_SESSION['__administration_group__']."/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">
			Area amministrazione e segreteria
		</div>
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
</body>
</html>
