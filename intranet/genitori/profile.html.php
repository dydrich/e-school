<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
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
<?php //include "sons_menu.php" ?>
<?php include "profile_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Gestione profilo
	</div>
	<div id="welcome">
		<p id="w_head">Gestisci i tuoi dati</p>
		<p class="w_text">Da qui potrai gestire il tuo profilo: dati, contatti, ecc.</p>
		<p class="w_text">Attualmente puoi:</p>
		<ul>
			<li>modificare la tua password personale;</li>
			<li>gestire i tuoi dati personali: contatti, indirizzi, telefono, ...</li>
		</ul>
	</div>
</div>
<p class="spacer"></p>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
