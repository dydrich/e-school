<?php 

require_once "../../lib/start.php";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$navigation_label = "Registro elettronico ".strtolower($_SESSION['__school_level__'][$ordine_scuola]);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype_js.php"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js.php"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "working.php" ?>
</div>
<div id="left_col">
	<div id="welcome">
		<p id="w_head" class="attention" style="font-weight: bold; font-size: 1.3em">Accesso negato</p>
		<p class="w_text attention" style="font-weight: bold">Stai cercando di accedere ad una pagina per la quale non sei stato autorizzato.</p>
		<p class="w_text attention">Se credi si tratti di un errore, <a href='mailto:<?php echo $_SESSION['__config__']['admin_email'] ?>?subject=Problema di accesso' style='text-decoration: underline'>contatta l'amministratore</a></p>
		<p class="w_text">&middot; Oppure <a href="<?php echo $_SESSION['__referer__'] ?>">torna alla pagina in cui ti trovavi.</a></p>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
