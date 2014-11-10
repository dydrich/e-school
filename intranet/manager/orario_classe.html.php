<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="outline_line_wrapper" style="margin-top: 20px">
		<div style="width: 20%; float: left; position: relative; top: 30%; left: 5%">Ora</div>
		<div style="width: 30%; float: left; position: relative; top: 30%">Luned&igrave;</div>
		<div style="width: 27%; float: left; position: relative; top: 30%">Marted&igrave;</div>
		<div style="width: 20%; float: left; position: relative; top: 30%">Mercoled&igrave;</div>
	</div>
	<table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%; border-collapse: collapse">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        	//print $classe;
        ?>
        <tr>
	        <td style="width: 7%; border: 1px solid #c0c0c0"><?php print $inizio_ore[$i+1] ?></td>
	        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 1, $i+1)])) print $materie[$orario_classe->getMateria($cls, 1, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 1, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 1, $i+1).")") ?></a></td>
	        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 2, $i+1)])) print $materie[$orario_classe->getMateria($cls, 2, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 2, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 2, $i+1).")") ?></a></td>
	        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 3, $i+1)])) print $materie[$orario_classe->getMateria($cls, 3, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 3, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 3, $i+1).")") ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
            <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>
    <div class="outline_line_wrapper" style="margin-top: 30px">
		<div style="width: 20%; float: left; position: relative; top: 30%; left: 5%">Ora</div>
		<div style="width: 30%; float: left; position: relative; top: 30%">Gioved&igrave;</div>
		<div style="width: 27%; float: left; position: relative; top: 30%">Venerd&igrave;</div>
		<div style="width: 20%; float: left; position: relative; top: 30%">Sabato</div>
	</div>
    <table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%; border-collapse: collapse">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr>
        <td style="width: 7%; border: 1px solid #c0c0c0"><?php print $inizio_ore[$i+1] ?></td>
        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 4, $i+1)])) print $materie[$orario_classe->getMateria($cls, 4, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 4, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 4, $i+1).")") ?></a></td>
        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 5, $i+1)])) print $materie[$orario_classe->getMateria($cls, 5, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 5, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 5, $i+1).")") ?></a></td>
        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 6, $i+1)])) print $materie[$orario_classe->getMateria($cls, 6, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 6, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 5, $i+1).")") ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
        	<td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 360px">
		<div class="drawer_label"><span>Classe <?php echo $_REQUEST['desc'] ?></span></div>
		<div class="drawer_link submenu"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=cdc&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Elenco docenti</a></div>
		<div class="drawer_link submenu"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=alunni&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/35.png" style="margin-right: 10px; position: relative; top: 5%" />Elenco alunni</a></div>
		<div class="drawer_link submenu separator"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=orario&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/70.png" style="margin-right: 10px; position: relative; top: 5%" />Orario delle lezioni</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<?php if ($_SESSION['__role__'] == "Dirigente scolastico"): ?>
			<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
		<?php endif; ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
