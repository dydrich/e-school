<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Area genitori
	</div>
	<div class="welcome">
		<p id="w_head">Bentornato <?php echo $_SESSION['__user__']->getFullName() ?></p>
		<p class="w_text">
			Sei entrato nell'area riservata ai genitori della intranet della <?php echo $_SESSION['__config__']['intestazione_scuola'] ?>.<br />
			In quest'area avrai accesso a varie informazioni riguardanti i tuoi figli e potrai comunicare con il personale della scuola, sia docente che non docente.<br />
		</p>
	</div>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_msg.php'; ?>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
