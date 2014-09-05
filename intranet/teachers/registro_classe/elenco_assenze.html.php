<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Elenco assenze</title>
<link rel="stylesheet" href="reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
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
<div id="popup_main">
	<p class="popup_header">Elenco assenze di <?php print $alunno['cognome']." ".$alunno['nome'] ?> [ <a href="pdf_absences.php?alunno=<?php print $id_alunno ?>">PDF</a> ] </p>
<form class="popup_form">
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('settembre')" style="text-decoration: none; font-weight: bold; ">Mese di settembre: <?php print count($assenze['09']) ?> assenze</a>
</div>
<div id="settembre" style="display: none; text-align: left">
<?php
setlocale("LC_TIME", "it_IT.utf_8");
foreach ($assenze['09'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('ottobre')" style="text-decoration: none; font-weight: bold; ">Mese di ottobre: <?php print count($assenze['10']) ?> assenze</a>
</div>
<div id="ottobre" style="display: none; text-align: left">
<?php 
foreach ($assenze['10'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('novembre')" style="text-decoration: none; font-weight: bold; ">Mese di novembre: <?php print count($assenze['11']) ?> assenze</a>
</div>
<div id="novembre" style="display: none; text-align: left">
<?php 
foreach ($assenze['11'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('dicembre')" style="text-decoration: none; font-weight: bold; ">Mese di dicembre: <?php print count($assenze['12']) ?> assenze</a>
</div>
<div id="dicembre" style="display: none; text-align: left">
<?php 
foreach ($assenze['12'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('gennaio')" style="text-decoration: none; font-weight: bold; ">Mese di gennaio: <?php print count($assenze['01']) ?> assenze</a>
</div>
<div id="gennaio" style="display: none; text-align: left">
<?php 
foreach ($assenze['01'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('febbraio')" style="text-decoration: none; font-weight: bold; ">Mese di febbraio: <?php print count($assenze['02']) ?> assenze</a>
</div>
<div id="febbraio" style="display: none; text-align: left">
<?php 
foreach ($assenze['02'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('marzo')" style="text-decoration: none; font-weight: bold; ">Mese di marzo: <?php print count($assenze['03']) ?> assenze</a>
</div>
<div id="marzo" style="display: none; text-align: left">
<?php 
foreach ($assenze['03'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('aprile')" style="text-decoration: none; font-weight: bold; ">Mese di aprile: <?php print count($assenze['04']) ?> assenze</a>
</div>
<div id="aprile" style="display: none; text-align: left">
<?php 
foreach ($assenze['04'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('maggio')" style="text-decoration: none; font-weight: bold; ">Mese di maggio: <?php print count($assenze['05']) ?> assenze</a>
</div>
<div id="maggio" style="display: none; text-align: left">
<?php 
foreach ($assenze['05'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
<div style="padding-left: 15px; font-size: 11px; text-align: left; font-weight: bold">
	<a href="#" onclick="show_div('giugno')" style="text-decoration: none; font-weight: bold; ">Mese di giugno: <?php print count($assenze['06']) ?> assenze</a>
</div>
<div id="giugno" style="display: none; text-align: left">
<?php 
foreach ($assenze['06'] as $abs){
	$giorno_str = strftime("%A", strtotime($abs));
?>
<span style="padding-left: 40px; font-weight: normal; font-size: 11px; color: #222222">
<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
</span><br />
<?php 
}
?>
</div>
</form>
</div>
</body>
</html>
