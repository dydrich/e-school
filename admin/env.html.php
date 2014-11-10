<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/general.css" type="text/css" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../js/jquery.jeditable.mini.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
	$(function(){
		load_jalert();
		setOverlayEvent();
		$('.edit').editable('../shared/env_manager.php', {
			indicator : 'Saving...',
			tooltip   : 'Click to edit...'
		});
	});
</script>
<style>
	form {border: 0}
</style>
<title>Registro elettronico</title>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "dev_menu.php" ?>
	</div>
	<div id="left_col">
		<table class="admin_table" style="width: 90%; border-collapse: collapse; margin: 15px auto 0 auto">
			<tr class="accent_decoration">
				<td style="width: 40%; padding-left: 10px; font-weight: bold">Variabile</td>
				<td style="width: 60%; padding-left: 10px; font-weight: bold">Valore</td>
			</tr>
				<?php
					$res_env->data_seek(0);
					while($row = $res_env->fetch_assoc()){
						$k = $row['variabile'];
						$v = $row['valore'];
						if($_SESSION['__user__']->getUsername() == "rbachis"){
							$row['readonly'] = false;
						}
				?>
			<tr style="height: 30px">
				<td style="width: 40%; padding-left: 10px" id=""><?php print $k ?></td>
				<td style="width: 60%; padding-left: 10px;">
				<p id="<?php print $k ?>" class="edit" style="margin-top: auto; margin-bottom: auto"><?php echo stripslashes($v) ?></p>

				</td >
			</tr>
			<?php } ?>
		</table>
	</div>
	<p class="spacer"></p>
</div>
<?php include 'footer.php'; ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
