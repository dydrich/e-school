<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<style>
		div.welcome:last-of-type {
			padding-bottom: 35px;
		}
	</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "working.php" ?>
</div>
<div id="left_col">
	<div class="welcome">
		<p id="s_head"><?php echo $label ?></p>
		<?php
		if(!$free_day){
			for($x = 1; $x < 6; $x++){
				if (isset($schedule[$x])){
					$a = $schedule[$x];
					if($a['materia'] != ""){
						?>
						<p class="s_hour"><?php print $x." ora: ".$a['cl'].$a['sez']."::".$a['materia'] ?><?php if($a['att'] != "") print(" (".$a['att'].")"); else if($a['hw'] != "") print(" (ci sono compiti da correggere)") ?></p>
					<?php
					}
				}
				else {
					?>
					<p class="s_hour"><?php echo $x." ora:    -------" ?></p>
				<?php
				}
			}
		}
		?>
	</div>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_notices.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_msg.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_news.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_events.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_circ.php'; ?>
</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
