<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Sostegno</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script></script>
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
		Verifica registri di classe
	</div>
	<div style="width: 65%; margin: 30px auto 0 auto; text-align: left; font-size: 1.2em">
	<?php 
	while ($row = $res_classes->fetch_assoc()){
	?>
		<p style="width: 90%; border-bottom: 1px solid #CCC"><img src="../../images/13.png" style="margin-right: 15px"><a href="registro_classe.php?idc=<?php echo $row['id_classe'] ?>" style="text-decoration: none; font-size: 1em; text-transform: uppercase; font-weight: bold">Classe <?php echo $row['anno_corso'].$row['sezione'] ?></a></p>
	<?php 
	}
	?>
	</div>
<p class="spacer"></p>
</div>
</div>
<?php include "footer.php" ?>	
</body>
</html>
