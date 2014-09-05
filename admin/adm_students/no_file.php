<?php 

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inserimento alunni</title>
	<link href="../../css/site_themes/blue_red/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/skins/aqua/theme.css" type="text/css" rel="stylesheet"  />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
</head>
<body onload="$('fname').focus()">
<div id="header">
	<div class="wrap" style="text-align: center">
		<?php include "../header.php" ?>
	</div>
</div>
<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
	<p class="popup_label">File non trovato</p>
	<form>
	<p style="text-align: center">
		Il file <span class="attention"><?php echo $_SESSION['download'] ?></span> da te richiesto non &egrave; presente nel server.<br />
		Il problema &egrave; stato segnalato all'amministratore del sito, e sar&agrave; risolto al pi&ugrave; presto.<br /><br />
		Ti preghiamo di riprovare pi&ugrave; tardi e di scusare il disagio.
 	</p>
 	</form>
	</div>
</div>
<?php include "../footer.php" ?>
</body>
</html>
