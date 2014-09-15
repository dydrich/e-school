<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<style>
.w_text{
	margin: 0;
}
p.w_text:first-of-type{
	margin-top: 15px;
}
p.w_text:last-of-type{
	margin-bottom: 15px;
}
</style>
</head>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_msg.php'; ?>
	<div id="schedule">
		<p id="s_head"><?php echo $label ?></p>
	<?php 
	if(!$free_day){
		for($x = 1; $x < 6; $x++){
			$a = $schedule[$x];
			if($a['materia'] != "" && $a['materia'] != "Scegli"){
	?>
		<p class="s_hour"><?php print $x." ora::".$a['materia'] ?><?php if($a['att'] != "") print(" (".$a['att'].")"); else if($a['hw'] != "") print(" (ci sono compiti da correggere)") ?></p>
	<?php 
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
<?php include "last_grades.php" ?>
</div>
<p class="spacer"></p>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
