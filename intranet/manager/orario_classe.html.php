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
<style type="text/css">
td a {
	text-decoration: none
}
</style>
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
		<?php echo $label ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 7%; float: left; position: relative; top: 30%">Ora</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Luned&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Marted&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Mercoled&igrave;</div>
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
    <div class="outline_line_wrapper">
		<div style="width: 7%; float: left; position: relative; top: 30%">Ora</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Gioved&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Venerd&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Sabato</div>
	</div>
    <table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%; border-collapse: collapse">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr>
        <td style="width: 7%; border: 1px solid #c0c0c0"><?php print $inizio_ore[$i+1] ?></td>
        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 1, $i+4)])) print $materie[$orario_classe->getMateria($cls, 4, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 4, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 4, $i+1).")") ?></a></td>
        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 1, $i+5)])) print $materie[$orario_classe->getMateria($cls, 5, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 5, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 5, $i+1).")") ?></a></td>
        <td style="width: 31%; border: 1px solid #c0c0c0"><a href="#"><?php if (isset($materie[$orario_classe->getMateria($cls, 1, $i+6)])) print $materie[$orario_classe->getMateria($cls, 6, $i+1)] ?><?php if($orario_classe->getDescrizione($cls, 6, $i+1) != "") print (" (".$orario_classe->getDescrizione($cls, 5, $i+1).")") ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
        	<td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="height: 40px; text-align: right"><a href="elenco_classi.php" style="text-transform: uppercase"><img src="../../images/back.png" style="margin-right: 8px; position: relative; top: 5px" />Torna all'elenco classi</a></td>
        </tr>    
    </table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
