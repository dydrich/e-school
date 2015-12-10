<?php

require_once "../../lib/start.php";

$navigation_label = "Home Page";
$drawer_label = "Problema di accesso";

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<div class="smallbox" id="working">
			<p class="menu_label class_icon">PProblema di accesso</p>
		</div>
	</div>
	<div id="left_col">
		<div id="welcome">
			<p id="w_head" class="attention" style="font-weight: bold; font-size: 1.3em">Attenzione</p>
			<p class="w_text attention" style="font-weight: bold">Hai eseguito l'accesso con un account Genitore, ma non risulta alcun alunno a te associato.</p>
			<p class="w_text attention">Se credi si tratti di un errore, <a href='mailto:<?php echo $_SESSION['__config__']['admin_email'] ?>?subject=Genitore non associato' style='text-decoration: underline'>contatta l'amministratore</a></p>
			<p class="w_text">&middot; Oppure <a href="../../shared/do_logout.php">torna alla pagina di login.</a></p>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
