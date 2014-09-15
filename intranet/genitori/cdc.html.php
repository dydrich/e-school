<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<style type="text/css">
tr:hover {
	background-color: rgba(30, 67, 137, .1);
}
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Elenco docenti del consiglio di classe <?php echo $_SESSION['__classe__']->get_anno(),$_SESSION['__classe__']->get_sezione() ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 40%; float: left; position: relative; top: 30%">Materia</div>
		<div style="width: 60%; float: left; position: relative; top: 30%">Docente</div>
	</div>
	<table style="width: 90%; margin-right: auto; margin-left: auto; text-align: left; border-collapse: collapse">
	<?php 
	foreach ($cdc as $a){
	?>
	<tr class="manager_row_small">
        <td style="width: 50%; padding-left: 30px; font-weight: bold"><?php print $a[1] ?></td>
        <td style="width: 50%; padding-left: 90px"><?php print $a[0] ?></td>
    </tr>
	<?php 
	}
	?>           
</table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
