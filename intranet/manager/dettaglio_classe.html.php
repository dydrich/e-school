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
		<div style="width: 35%; float: left; position: relative; top: 30%"><span style="padding-left: 10px">Alunno</span></div>
		<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Assenze</div>
		<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">% assenze</div>
		<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Ore assenza</div>
		<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">% ore assenza</div>
	</div>
	<table style="width: 95%; margin: 0 auto 0 auto">
            
			<?php 
			$idx = 0;
			while($al = $res_assenze_alunni->fetch_assoc()){
				$assenze = $totali['giorni'] - $al['giorni'];
				$perc_assenze = round((($assenze / $totali['giorni']) * 100), 2);
				/**
				 * calcolo della percentuale oraria di assenze mediante conversione
				 * dei time in secondi
				 */
				// numero totale di ore di lezione (in secondi)
				$tot_hours = $totali['ore'];
				// ore di assenza (in secondi)
				$abs_hours = $al['ore_assenza'];
				$perc_hours = round((($abs_hours / $tot_hours) * 100), 2);
				// formattazione ore assenza
				$abs_sec = $abs_hours%60;
				$t_m = $abs_hours - $abs_sec;
				$t_m /= 60;
				$ore_assenza = minutes2hours($t_m, "-");
			?>
			<tr class="bottom_decoration">
				<td style="width: 35%; padding-left: 8px;"><?php print $al['cognome']." ".$al['nome']?></td>
				<td style="width: 15%; text-align: center;"><?php print $assenze ?></td>
				<td style="width: 15%; text-align: center;<?php if($perc_assenze > 24.99) print("font-weight: bold") ?>" <?php if($perc_assenze > 24.99) print("class='attention'") ?>><?php print $perc_assenze ?> %</td>
				<td style="width: 15%; text-align: center;"><?php print $ore_assenza ?></td>
				<td style="width: 15%; text-align: center;<?php if($perc_assenze > 24.99) print("font-weight: bold") ?>" <?php if($perc_assenze > 24.99) print("class='attention'") ?>><?php print $perc_hours ?> %</td>
			</tr>
			<?php
				$idx++; 
			}
			?>
			<tr style="height: 30px; vertical-align: middle" class="bottom_decoration">
				<td style="text-align: left; font-weight: bold; padding-left: 8px">Dati complessivi</td>
				<td colspan="2" style="text-align: center; font-weight: bold">Giorni di lezione: <?php print $totali['giorni'] ?> (<span class="attention"><?php print $totali['limite_giorni'] ?></span>)</td>
				<td colspan="2" style="text-align: center; font-weight: bold">Ore di lezione: <?php print $ore ?> (<span class="attention"><?php print $ore2.":".$minuti2 ?></span>)</td>
			</tr>
			</table>	
	</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
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
