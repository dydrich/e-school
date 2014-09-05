<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<style type="text/css">
td a {
	text-decoration: none
}
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php if($area == "genitori") include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Orario delle lezioni, classe <?php echo $_SESSION['__classe__']->get_anno(),$_SESSION['__classe__']->get_sezione() ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 7%; float: left; position: relative; top: 30%">Ora</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Luned&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Marted&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Mercoled&igrave;</div>
	</div>
	<table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%; border-collapse: collapse">
		<tr class="manager_row_small">
	        <td style="width: 7%"></td>
	        <td style="width: 31%">Ingresso ore: <?php echo $schedule_module->getDay(1)->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Ingresso ore: <?php echo $schedule_module->getDay(2)->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Ingresso ore: <?php echo $schedule_module->getDay(3)->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
        </tr>
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        	$lun = $orario_classe->searchHour(1, $i + 1, $classe);
        	$mar = $orario_classe->searchHour(2, $i + 1, $classe);
        	$mer = $orario_classe->searchHour(3, $i + 1, $classe);
        ?>
        <tr class="manager_row_small">
	        <td style="width: 7%"><?php print $i+1 ?></td>
	        <td style="width: 31%"><a href="#"><?php if($lun) print $materie[$lun->getMateria()]; if($lun && $lun->getDescrizione() != "") print (" (".$lun->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($mar) print $materie[$mar->getMateria()]; if($mar && $mar->getDescrizione() != "") print (" (".$mar->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($mer) print $materie[$mer->getMateria()]; if($mer && $mer->getDescrizione() != "") print (" (".$mer->getDescrizione().")") ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr class="manager_row_small">
	        <td style="width: 7%"></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(1)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(2)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(3)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
        </tr>
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
    	<tr class="manager_row_small">
	        <td style="width: 7%"></td>
	        <td style="width: 31%">Ingresso ore: <?php echo $schedule_module->getDay(4)->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Ingresso ore: <?php echo $schedule_module->getDay(5)->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%"><?php if ($schedule_module->getDay(6)): ?>Ingresso ore: <?php echo $schedule_module->getDay(6)->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?><?php endif; ?></td>
        </tr>
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        	$gio = $orario_classe->searchHour(4, $i + 1, $classe);
        	$ven = $orario_classe->searchHour(5, $i + 1, $classe);
	        if ($schedule_module->getDay(6)){
        	    $sab = $orario_classe->searchHour(6, $i + 1, $classe);
	        }
	        else {
		        $sab = null;
	        }
        ?>
        <tr class="manager_row_small">
	        <td style="width: 7%"><?php print $i+1 ?></td>
	        <td style="width: 31%"><a href="#"><?php if($gio) print $materie[$gio->getMateria()]; if($gio && $gio->getDescrizione() != "") print (" (".$gio->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($ven) print $materie[$ven->getMateria()]; if($ven && $ven->getDescrizione() != "") print (" (".$ven->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($sab) print $materie[$sab->getMateria()]; if($sab && $sab->getDescrizione() != "") print (" (".$sab->getDescrizione().")") ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr class="manager_row_small">
	        <td style="width: 7%"></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(4)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(5)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%"><?php if ($schedule_module->getDay(6)): ?>Uscita ore: <?php echo $schedule_module->getDay(6)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?><?php endif; ?></td>
        </tr>
        <tr>
        	<td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>    
    </table>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
