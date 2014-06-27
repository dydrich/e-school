<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
function show_div(div, elem){
	if($(div).style.display == "none"){
		Effect.BlindDown(div, { duration: 1.0 });
		parent = elem.parentNode;
		parent.style.backgroundColor = "rgba(211, 222, 199, 0.8)";
	}
	else{
		Effect.SlideUp(div, { duration: 1.0 });
		parent = elem.parentNode;
		parent.style.backgroundColor = "";
	}
}
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
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Dettaglio assenze <?php print $alunno['cognome']." ".$alunno['nome'] ?>
	</div>
	<div style="width: 95%; margin: auto; height: 25px; text-align: center; text-transform: uppercase; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 33%; float: left; position: relative; top: 30%">Assenze: <?php print $tot_assenze ?></div>
		<div style="width: 33%; float: left; position: relative; top: 30%">Ritardi: <?php print $somma_ritardi['giorni_ritardo']?> (<?php print substr($somma_ritardi['ore_ritardo'], 0, 5) ?>)</div>
		<div style="width: 33%; float: left; position: relative; top: 30%">Uscite anticipate: <?php print $somma_uscite['giorni_anticipo']?> (<?php print substr($somma_uscite['ore_perse'], 0, 5) ?>)</div>
	</div>
    <table style="width: 95%; margin: 20px auto 0 auto">
            <?php 
			$x = 9;
			if($quadrimestre == 2)
				$x = 2;
			foreach($mesi as $mese){
				if($x == 13)
					$x = 1;
				$x_str = $x;
				if(strlen($x_str) < 2){
					$x_str = "0".$x;
				}
			?>
		<tr style="border-bottom: 1px solid #C0C0C0">
            <td style="width: 33%">	
            	<div style="padding-left: 15px; text-align: left; font-weight: normal; height: 15px; padding-top: 8px;">
					<a href="#" onclick="show_div('<?php print $mese ?>_assenza', this)" style="text-decoration: none; <?php if(count($assenze[$x_str])) print("font-weight: bold") ?>">Mese di <?php print $mese ?>: <?php print count($assenze[$x_str]) ?> assenze</a>
				</div>
				<div id="<?php print $mese ?>_assenza" style="display: none; text-align: left; margin-bottom: 0">
				<?php 
				foreach ($assenze[$x_str] as $abs){
					$giorno_str = strftime("%A", strtotime($abs));
				?>
				<span style="padding-left: 40px; font-weight: normal">
				<?php print utf8_encode($giorno_str)." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
				</span><br />
				<?php 
				}
				?>
					<span>&nbsp;</span>
				</div>
            </td>
            <td style="width: 33%">
				<div style="padding-left: 15px; text-align: left; font-weight: normal; height: 15px; padding-top: 8px;">
					<a href="#" onclick="show_div('<?php print $mese ?>_anticipata', this)" style='text-decoration: none; <?php if(count($ritardi[$x_str])) print("font-weight: bold") ?>'>Mese di <?php print $mese ?>: <?php print count($ritardi[$x_str]) ?> ritardi</a>
				</div>
				<div id="<?php print $mese ?>_anticipata" style="display: none; text-align: left; margin-bottom: 0">&nbsp;
					<?php 
					foreach($ritardi[$x_str] as $day){
						$giorno_str = strftime("%A", strtotime($day['data']));
					?>
						<span style=""><?php print utf8_encode($giorno_str)." ".format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>: ore <?php print substr($day['ingresso'], 0, 5) ?></span><br />
					<?php 
					}
					?>	
					<span>&nbsp;</span>				
				</div>
			</td>
            <td style="width: 33%">
				<div style="padding-left: 15px; text-align: left; font-weight: normal; height: 15px; padding-top: 8px;">
					<a href="#" onclick="show_div('<?php print $mese ?>_ritardo', this)" style='text-decoration: none; <?php if(count($uscite[$x_str])) print("font-weight: bold") ?>'>Mese di <?php print $mese ?>: <?php print count($uscite[$x_str]) ?> anticipi</a>
				</div>
				<div id="<?php print $mese ?>_ritardo" style="display: none; text-align: left; margin-bottom: 15px">&nbsp;
					<?php 
					foreach($uscite[$x_str] as $day){
						$giorno_str = strftime("%A", strtotime($day['data']));
					?>
						<span style=""><?php print utf8_encode($giorno_str)." ".format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>: ore <?php print substr($day['uscita'], 0, 5) ?></span><br />
					<?php 
					}
					?>	
					<span>&nbsp;</span>				
				</div>
			</td>
            </tr>
			<?php
				$x++;
				if($quadrimestre == 1 && $x == 2)
					break;
			} 
			?> 
            </table>
			</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>	
</body>
</html>
