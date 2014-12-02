<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Registro di sostegno</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
	$(function(){
		load_jalert();
		setOverlayEvent();
	});
</script>
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
			<li style="margin-top: 15px">Padre: <?php if(isset($student['dati']['padre'])) echo $student['dati']['padre'] ?></li>
			<li>Madre: <?php if(isset($student['dati']['madre'])) echo $student['dati']['madre'] ?></li>
			<li>Fratelli e sorelle: <?php if(isset($student['dati']['fratelli_sorelle'])) echo $student['dati']['fratelli_sorelle'] ?></li>
			<li>Altri componenti: <?php if (isset($student['dati']['other'])) echo $student['dati']['other'] ?></li>
			<li style="margin-top: 15px">Scuola di provenienza: <?php if (isset($student['dati']['scuola_provenienza'])) echo $student['dati']['scuola_provenienza'] ?></li>
			<li>Classe di provenienza: <?php if (isset($student['dati']['classe_provenienza'])) echo $student['dati']['classe_provenienza'] ?></li>
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
