<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Elenco ritardi</title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
	function show_div(div){
		$('#'+div).toggle(500);
	}
</script>
</head>
<body class="popup_body">
<div id="popup_main" style="min-height: 420px">
	<p class="popup_header"><?php print $alunno['cognome']." ".$alunno['nome'] ?></p>
<form class="popup_form no_border">
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
	$label_del = "nessuno";
	if (isset($ritardi[$x_str]) && count($ritardi[$x_str]) > 0) {
		$label_del = count($ritardi[$x_str]);
	}
	$label_early = "nessuna";
	if (isset($uscite[$x_str]) && count($uscite[$x_str]) > 0) {
		$label_early = count($uscite[$x_str]);
	}
?>
	<p style="margin-left: 10px; height: 10px; text-align: left; margin-bottom: 0px; padding-bottom: 0px">
		<a href="#" onclick="show_div('<?php print $mese ?>')" style='text-decoration: none; font-weight: bold; color: #373946'>Mese di <?php print $mese ?></a>
	</p>
	<div id="<?php print $mese ?>" style="text-align: left; display: none; margin-left: 15px; margin-top: 10px; margin-bottom: 15px">
		<a href="#" onclick="show_div('<?php print $mese?>_ritardi')">Ritardi: <?php echo $label_del ?></a><br />
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
		<a href="#" onclick="show_div('<?php print $mese?>_uscite')">Uscite anticipate: <?php echo $label_early ?></a>
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
