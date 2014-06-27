<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Elenco ritardi</title>
<link rel="stylesheet" href="reg_classe_popup.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
function show_div(div){
	if($(div).style.display == "none")
		Effect.BlindDown(div, { duration: 1.0 });
	else
		Effect.SlideUp(div, { duration: 1.0 });
}
</script>
</head>
<body>
<div id="main">
<form>
	<p style="padding-top: 10px; margin: auto; font-weight: bold; font-size: 12;padding-bottom: 10px; text-align: center"><?php print $alunno['cognome']." ".$alunno['nome'] ?></p>
	<p style="text-align: left; padding-top: 0px; margin-left: 20px; font-weight: normal; font-size: 11; height: 15px; margin-bottom: 20px">
		Ritardi: <?php print $somma_ritardi['giorni_ritardo']; if($somma_ritardi['giorni_ritardo'] > 0) {?> per un totale di <?php print substr($somma_ritardi['ore_ritardo'], 0, 5) ?> [ <a href="pdf_delay.php?alunno=<?php print $id_alunno ?>&q=0">PDF</a> ]<?php } ?><br />
		Uscite anticipate: <?php print $somma_uscite['giorni_anticipo']; if($somma_uscite['giorni_anticipo']){ ?> per un totale di <?php print substr($somma_uscite['ore_perse'], 0, 5) ?> [ <a href="pdf_early_exit.php?alunno=<?php print $id_alunno ?>&q=0">PDF</a> ]<?php } ?>
	</p>
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
	<p style="margin-left: 10px; height: 10px; text-align: left; margin-bottom: 0px; padding-bottom: 0px">
		<a href="#" onclick="show_div('<?php print $mese ?>')" style='text-decoration: none; font-weight: bold; color: #373946'>Mese di <?php print $mese ?></a>
	</p>
	<div id="<?php print $mese ?>" style="text-align: left; display: none; margin-left: 15px; margin-top: 10px; margin-bottom: 15px">
		<a href="#" onclick="show_div('<?php print $mese?>_ritardi')">Ritardi: <?php print count($ritardi[$x_str]) ?></a><br />
		<div id="<?php print $mese."_ritardi" ?>" style="text-align: left; display: none; margin-left: 15px; margin-top: 0px; margin-bottom: 15px">
		<?php 
		foreach($ritardi[$x_str] as $day){
			$giorno_str = utf8_encode(strftime("%A", strtotime($day['data'])));
		?>
			<span style="color: #222222"><?php print $giorno_str." ".format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>: ore <?php print substr($day['ingresso'], 0, 5) ?></span><br />
		<?php 
		}
		?>
		</div> 
		<a href="#" onclick="show_div('<?php print $mese?>_uscite')">Uscite anticipate: <?php print count($uscite[$x_str]) ?></a>
		<div id="<?php print $mese."_uscite" ?>" style="text-align: left; display: none; margin-left: 15px; margin-top: 0px; margin-bottom: 15px">
		<?php 
		foreach($uscite[$x_str] as $day){
			$giorno_str = utf8_encode(strftime("%A", strtotime($day['data'])));
		?>
			<span style="color: #222222"><?php print $giorno_str." ".format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>: ore <?php print substr($day['uscita'], 0, 5) ?></span><br />
		<?php 
		}
		?>
		</div>
	</div>
<?php
	$x++;
	if($quadrimestre == 1 && $x == 2)
		break;
} 
?>
<div style="height: 30px"></div>
</form>
</div>
</body>
</html>