<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - Obiettivi didattici</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
		<div id="welcome">
			<p id="w_head" style="font-weight: bold">Obiettivi didattici:</p>
		</div>
		<table style="width: 500px; margin-left: 50px">
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
		<tr style="height: 40px">
				<td colspan="2" style="font-weight: bold; text-align: center"><?php echo $mat ?></td>
			</tr>
	<?php
	foreach ($r as $row){
		$color = "";
		if (!$row['children']){
			$color = "rgba(77, 84, 95, 1)";
		}
		else {
			$color = "rgba(211, 222, 199, .4)";
		}
		?>
				<tr style="border-bottom: 1px solid <?php echo $color ?>; height: 25px">
					<td style="width: 80%"><a href="obiettivo.php?oid=<?php echo $row['id'] ?>" style="text-decoration: none; font-weight: bold"><?php echo $row['nome'] ?></a></td>
					<td style="width: 20%"><?php echo join(", ", $row['classi']) ?></td>
				</tr>
		<?php
				if ($row['children']){
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
	}
	?>
			<tr style="height: 20px">
				<td colspan="2"></td>
			</tr>
			<tr style="height: 30px; font-weight: bold; text-align: right">
				<td colspan="2" style="text-align: center">
					<div style="margin-left: 70%; width: 30%; height: 20px; border: 1px solid rgb(211, 222, 199); border-radius: 8px; background-color: rgba(211, 222, 199, 0.4)">
						<a href="obiettivo.php?oid=0" style="text-decoration: none; position: relative; top: 10%">Nuovo obiettivo</a>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
