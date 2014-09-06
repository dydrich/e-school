<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
</head>
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
		<?php print $current_class->to_string()." - Statistiche di presenza " ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 35%; float: left; position: relative; top: 30%">Alunno</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Assenze</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">% assenze</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Ore assenza</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">% ore assenza</div>
	</div>
	<table style="width: 95%; margin: 20px auto 0 auto">
            
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
			<tr class="manager_row_small">
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
			<tr class="manager_row_small">
				<td colspan="5"></td>
			</tr>
			<tr class="manager_row_menu">
				<td style="text-align: left; font-weight: bold; padding-left: 8px">Dati complessivi</td>
				<td colspan="2" style="text-align: center; font-weight: bold">Giorni di lezione: <?php print $totali['giorni'] ?> (<span class="attention"><?php print $totali['limite_giorni'] ?></span>)</td>
				<td colspan="2" style="text-align: center; font-weight: bold">Ore di lezione: <?php print $ore ?> (<span class="attention"><?php print $ore2.":".$minuti2 ?></span>)</td>
			</tr>
			</table>	
	</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>	
</body>
</html>
