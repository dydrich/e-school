<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - Obiettivi didattici</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script>
	$(function(){
		load_jalert();
		$('#new_goal').button();

		$('#new_goal').click(function(event) {
				document.location.href = "obiettivo.php?oid=0";
		});
	});
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Obiettivi didattici</div>
		<table style="width: 80%; margin: auto">
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
			<tr style="border-bottom: 1px solid <?php echo $color ?>; height: 25px">
				<td style="width: 80%"><a href="obiettivo.php?oid=<?php echo $row['id'] ?>" style="text-decoration: none; font-weight: bold"><?php echo $row['nome'] ?></a></td>
				<td style="width: 20%"><?php echo join(", ", $row['classi']) ?></td>
			</tr>
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
			<tr style="border-bottom: 1px solid <?php echo $color ?>">
				<td style="width: 80%; padding-left: 20px"><a href="obiettivo.php?oid=<?php echo $child['id'] ?>" style="text-decoration: none"><?php echo $child['nome'] ?></a></td>
				<td style="width: 80%"><?php echo join(", ", $child['classi']) ?></td>
			</tr>
	<?php 
				}
			}
		}
	}
	else{
		foreach ($goals as $j => $r){
			$mat = $db->executeCount("SELECT materia FROM rb_materie WHERE id_materia = {$j}");
	?>
		<tr style="height: 40px; border-bottom: 1px solid rgba(30, 67, 137, 1)">
			<td colspan="2" style="font-weight: bold; text-align: center"><?php echo $mat ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
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
				<tr style="border-bottom: 1px solid <?php echo $color ?>; height: 25px">
					<td style="width: 80%"><a href="obiettivo.php?oid=<?php echo $row['id'] ?>" style="text-decoration: none; font-weight: bold"><?php echo $row['nome'] ?></a></td>
					<td style="width: 20%"><?php echo join(", ", $row['classi']) ?></td>
				</tr>
		<?php
				if (isset($row['children'])){
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
				<tr style="border-bottom: 1px solid <?php echo $color ?>">
					<td style="width: 80%; padding-left: 20px"><a href="obiettivo.php?oid=<?php echo $child['id'] ?>" style="text-decoration: none"><?php echo $child['nome'] ?></a></td>
					<td style="width: 80%"><?php echo join(", ", $child['classi']) ?></td>
				</tr>
		<?php 
					}
				}
			}
		} 
	}
	?>
			<tr style="height: 20px">
				<td colspan="2"></td>
			</tr>
			<tr style="height: 30px; font-weight: bold; text-align: right">
				<td colspan="2" style="text-align: right">
					<button id="new_goal">Nuovo obiettivo</button>
				</td>
			</tr>
		</table>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
