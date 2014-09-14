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
		<div class="group_head">Modifica variabili d'ambiente</div>
			<table style="width: 90%; border-collapse: collapse; margin: 20px auto 0 auto">
				<tr class="title_row">
					<td colspan="2" style="border: 1px solid #1E4389"><?php print $_SESSION['__current_year__']->to_string() ?></td>
				</tr>
				<tr class="admin_row_small">
					<td style="width: 40%; border: 1px solid #1E4389; padding-left: 10px; font-weight: bold">Variabile</td>
					<td style="width: 60%; border: 1px solid #1E4389; padding-left: 10px; font-weight: bold">Valore</td>
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
					<td style="width: 40%; border: 1px solid #1E4389; padding-left: 10px" id=""><?php print $k ?></td>
					<td style="width: 60%; border: 1px solid #1E4389; padding-left: 10px;">
					<p id="<?php print $k ?>" class="edit" style="margin-top: auto; margin-bottom: auto"><?php echo stripslashes($v) ?></p>

					</td >	
				</tr>
				<?php } ?>
			</table>
		</div>
		<p class="spacer"></p>
	</div>
<?php include 'footer.php'; ?>
</body>
</html>
