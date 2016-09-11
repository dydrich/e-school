<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
	</script>
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
	<?php if(is_installed("messenger")) include $_SESSION['__path_to_root__'].'modules/messenger/w_msg.php'; ?>
	<div id="schedule">
		<p id="s_head"><?php echo $label ?></p>
	<?php 
	if(!$free_day){
		for($x = 1; $x < 6; $x++){
			$a = $schedule[$x];
			if(isset($a['materia']) && $a['materia'] != "" && $a['materia'] != "Scegli"){
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=alunni"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=alunni"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
