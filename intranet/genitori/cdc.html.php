<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<style type="text/css">
tr:hover {
	background-color: rgba(211, 222, 199, 0.6);
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
	<div style="width: 90%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Elenco docenti del consiglio di classe <?php echo $_SESSION['__classe__']->get_anno(),$_SESSION['__classe__']->get_sezione() ?>
	</div>
	<div style="width: 95%; margin: 0 auto 20px auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 40%; float: left; position: relative; top: 30%">Materia</div>
		<div style="width: 60%; float: left; position: relative; top: 30%">Docente</div>
	</div>
	<table style="width: 90%; margin-right: auto; margin-left: auto; text-align: left; border-collapse: collapse">
	<?php 
	foreach ($cdc as $a){
	?>
	<tr style="border-bottom: 1px solid rgba(211, 222, 199, 0.6); height: 20px">
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
