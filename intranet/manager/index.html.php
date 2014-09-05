<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
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
		<p id="w_head">Bentornato <?php echo $_SESSION['__user__']->getFullName() ?></p>
		<p class="w_text" style="width: 350px">
			<?php if (isset($_SESSION['__school_order__']) && isset($_SESSION['__school_level__'][$_SESSION['__school_order__']])){ ?>
			Sei nell'area dedicata alla <span style="font-weight: bold"><?php echo $_SESSION['__school_level__'][$_SESSION['__school_order__']] ?></span><br />
			<?php } ?>
			Cambia ordine di scuola<br />
			<?php
			foreach ($_SESSION['__school_level__'] as $k => $sl){
			?>
			<a href="#" onclick="select_level('<?php echo $_SESSION['__path_to_root__'] ?>', '<?php echo basename($_SERVER['PHP_SELF']) ?>', <?php echo $k ?>)" id="level_<?php echo $k ?>" class="school_level"><?php echo $sl ?></a><br />
			<?php } ?>
	 	</p>	
	</div>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_msg.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_news.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_events.php'; ?>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
