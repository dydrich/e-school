<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
var update_stats = function(){
	if(confirm("L'aggiornamento delle statistiche richiede alcuni minuti (circa 3). Sei sicuro di voler continuare?")){
		document.location.href = "registro.php?nocache=1&do=<?php print $_REQUEST['do'] ?>";
	}
	else{
		return false;
	}
}
</script>
<style type="text/css">
tbody tr:hover {
	background-color: rgba(30, 67, 137, .1);
}
</style>
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
		Statistiche assenze aggiornate al <?php print $data_creazione.", ore ".$ora_creazione ?>
		<a style="float: right; margin-right: 5px; font-size: 0.9em; color: #003366" href="#" onclick="update_stats()">Aggiorna statistiche</a>
	</div>
   	<?php include "registro_".$_REQUEST['do'].".php" ?>			
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>	
</body>
</html>
