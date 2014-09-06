<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Registro di sostegno</title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "menu_sostegno.php" ?>
</div>
<div id="left_col">
<?php 
if ($res_st->num_rows > 1){
?>
	<div class="welcome">
		<p id="w_head">Seleziona l'alunno</p>
		<p class="w_text" style="width: 350px">
			<?php
			while ($row = $res_st->fetch_assoc()){
			?>
			<a href="#" onclick="select_student('<?php echo $row['alunno'] ?>)" id="level_<?php echo $k ?>" class="school_level"><?php echo $sl ?></a><br />
			<?php } ?>
	 	</p>	
	</div>
<?php
}
if ($_SESSION['__sp_student__']){
	$student = $_SESSION['__sp_student__'];
?>
	<div class="welcome">
		<p id="w_head">Alunno: <?php echo $student['cognome']." ".$student['nome'] ?></p>
		<p class="w_text" style="width: 350px">
			Scheda personale:
	 	</p>
	 	<ul>
			<li>Data di nascita: <?php echo format_date($student['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></li>
			<li>Luogo di nascita: <?php echo $student['luogo_nascita'] ?></li>
			<li>Indirizzo: <?php if (isset($student['indirizzo']['indirizzo'])) echo $student['indirizzo']['indirizzo'] ?></li>
			<li>Telefono: <?php if (isset($student['indirizzo']['telefono1'])) echo $student['indirizzo']['telefono1'] ?></li>
			<li>Classe: <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></li>
			<li style="margin-top: 15px">Padre: <?php echo $student['dati']['padre'] ?></li>
			<li>Madre: <?php echo $student['dati']['madre'] ?></li>
			<li>Fratelli e sorelle: <?php echo $student['dati']['fratelli_sorelle'] ?></li>
			<li>Altri componenti: <?php if (isset($student['dati']['other'])) echo $student['dati']['other'] ?></li>
			<li style="margin-top: 15px">Scuola di provenienza: <?php echo $student['dati']['scuola_provenienza'] ?></li>
			<li>Classe di provenienza: <?php echo $student['dati']['classe_provenienza'] ?></li>
		</ul>	
	</div>
<?php
}
?>
</div>
<p class="spacer"></p>
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
