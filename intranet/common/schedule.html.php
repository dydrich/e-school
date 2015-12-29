<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});

		var _show = function(e, off) {
			if ($('#other_drawer').is(":visible")) {
				$('#other_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#other_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#other_drawer').show('slide', 300);
			return true;
		};
	</script>
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
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="outline_line_wrapper" style="margin-top: 25px; margin-bottom: 0">
		<div style="width: 22%; float: left; position: relative; top: 30%; text-align: center">&nbsp;</div>
		<div style="width: 29%; float: left; position: relative; top: 30%; text-align: left">Luned&igrave;</div>
		<div style="width: 27%; float: left; position: relative; top: 30%">Marted&igrave;</div>
		<div style="width: 18%; float: left; position: relative; top: 30%">Mercoled&igrave;</div>
	</div>
	<table style="margin: 0px auto 0 auto; text-align: center; font-size: 1em; width: 90%; border-collapse: collapse">
		<tr class="manager_row_small" style="font-weight: 600">
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
	        //echo $lun->getMateria();
        ?>
        <tr class="bottom_decoration">
	        <td style="width: 7%"><?php print $i+1 ?></td>
	        <td style="width: 31%"><a href="#"><?php if($lun != null) print $materie[$lun->getMateria()]; if($lun && $lun->getDescrizione() != "") print (" (".$lun->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($mar) print $materie[$mar->getMateria()]; if($mar && $mar->getDescrizione() != "") print (" (".$mar->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($mer) print $materie[$mer->getMateria()]; if($mer && $mer->getDescrizione() != "") print (" (".$mer->getDescrizione().")") ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr class="manager_row_small" style="font-weight: 600">
	        <td style="width: 7%"></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(1)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(2)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
	        <td style="width: 31%">Uscita ore: <?php echo $schedule_module->getDay(3)->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>
    <div class="outline_line_wrapper" style="margin-top: 25px">
		<div style="width: 22%; float: left; position: relative; top: 30%">&nbsp;</div>
		<div style="width: 29%; float: left; position: relative; top: 30%">Gioved&igrave;</div>
		<div style="width: 27%; float: left; position: relative; top: 30%">Venerd&igrave;</div>
		<div style="width: 18%; float: left; position: relative; top: 30%">Sabato</div>
	</div>
    <table style="margin: 0px auto 0 auto; text-align: center; font-size: 1em; width: 90%; border-collapse: collapse">
    	<tr class="manager_row_small" style="font-weight: 600">
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
        <tr class="bottom_decoration">
	        <td style="width: 7%"><?php print $i+1 ?></td>
	        <td style="width: 31%"><a href="#"><?php if($gio) print $materie[$gio->getMateria()]; if($gio && $gio->getDescrizione() != "") print (" (".$gio->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($ven) print $materie[$ven->getMateria()]; if($ven && $ven->getDescrizione() != "") print (" (".$ven->getDescrizione().")") ?></a></td>
	        <td style="width: 31%"><a href="#"><?php if($sab) print $materie[$sab->getMateria()]; if($sab && $sab->getDescrizione() != "") print (" (".$sab->getDescrizione().")") ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr class="manager_row_small" style="font-weight: 600">
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1): ?>
			<div class="drawer_link separator">
				<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
			</div>
		<?php endif; ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $area ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $area ?>"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1){
	$height = 36 * (count($_SESSION['__sons__']));
	?>
	<div id="other_drawer" class="drawer" style="height: <?php echo $height ?>px; display: none; position: absolute">
		<?php
		$indice = 1;
		reset($_SESSION['__sons__']);
		while(list($key, $val) = each($_SESSION['__sons__'])){
			$cl = "";
			if ($key == $_SESSION['__current_son__']) {
				$cl = " _bold";
			}
			?>
			<div class="drawer_link">
				<a href="<?php print $page ?>?son=<?php print $key ?>" clas="<?php echo $cl ?>"><?php print $val[0] ?></a>
			</div>
		<?php
		}
		?>
	</div>
<?php
}
?>
</body>
</html>
