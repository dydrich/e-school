<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - Obiettivi didattici</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#new_goal').click(function(event) {
				document.location.href = "obiettivo.php?oid=0";
			});
			$('#new_goal').css({
				cursor: "pointer"
			});
		});
	</script>
	<style>
		div.subject:not(:nth-of-type(1)) {
			margin-top: 20px;
		}
	</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="width: 90%">
			<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -20px" class="rb_button">
				<a href="obiettivo.php?oid=0">
					<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
				</a>
			</div>
		</div>
		<div class="card_container">
	<?php
	if ($subject != 12 && $subject != 9){
		foreach ($goals as $row){
			$color = "";
			if (!isset($row['children'])){
				$color = "rgba(30, 67, 137, .8);";
			}
			else {
				$color = "#EF6C6C";
			} 
	?>
			<div class="card">
				<div class="card_title">
					<a href="obiettivo.php?oid=<?php echo $row['id'] ?>" style="text-decoration: none; font-weight: bold"><?php echo $row['nome'] ?></a>
					<div style="float: right; margin-right: 20px; width: 150px">
						<?php echo join(", ", $row['classi']) ?>
					</div>
				</div>
				<div class="card_varcontent">
	<?php
			if (isset($row['children'])){
				$c = count($row['children']);
				foreach ($row['children'] as $child){
					$c--;
					$color = "";
					if ($c == 0){
						$color = "rgba(77, 84, 95, 1)";
					}
					else {
						$color = "rgba(211, 222, 199, .4)";
					}
	?>
					<div class="minicard">
						<a href="obiettivo.php?oid=<?php echo $child['id'] ?>" style="text-decoration: none"><?php echo $child['nome'] ?></a>
					</div>
					<div class="minicard">
						<?php echo join(", ", $child['classi']) ?>
					</div>
	<?php 
				}
			}
		}
	?>
				</div>
			</div>
	<?php
	}
	else{

		foreach ($goals as $j => $r){
			$mat = $db->executeCount("SELECT materia FROM rb_materie WHERE id_materia = {$j}");
			?>
			<div class="subject" style="width: 50%; font-size: 1em; font-weight: normal; text-transform: uppercase; position: relative; color: #000000"><?php echo $mat ?></div>
		<?php

			foreach ($r as $row){
				$color = "";
				if (!isset($row['children'])){
					$color = "#DB5355";
				}
				else {
					$color = "rgba(30, 67, 137, .5)";
				}
				?>
			<div class="card">
				<div class="card_title">
					<a href="obiettivo.php?oid=<?php echo $row['id'] ?>" style="text-decoration: none; font-weight: bold"><?php echo $row['nome'] ?></a>
					<div style="float: right; margin-right: 20px; width: 150px; text-align: center">
						<?php echo join(", ", $row['classi']) ?>
					</div>
				</div>
		<?php
				if (isset($row['children'])){
					echo '<div class="card_varcontent" style="overflow: hidden">';
					$c = count($row['children']);
					foreach ($row['children'] as $child){
						$c--;
						$color = "";
						if ($c == 0){
							$color = "#DB5355";
						}
						else {
							$color = "rgba(30, 67, 137, .5)";
						}
		?>
					<div class="minicard" style="margin-left: 0">
						<a href="obiettivo.php?oid=<?php echo $child['id'] ?>" style="text-decoration: none"><?php echo $child['nome'] ?></a>
					</div>
					<div class="minicard" style="margin-left: 5%">
						<?php echo join(", ", $child['classi']) ?>
					</div>
		<?php
					}
					?>
					</div><!-- varcontent -->
				<?php
				}
				?>
		</div><!-- card -->
			<?php

			}

		}
	}
	?>
		</div><!-- card_container -->
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
