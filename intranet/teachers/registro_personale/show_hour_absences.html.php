<!DOCTYPE html>
<html>
<head>
<title>Registro personale</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">

</script>
</head>
<body class="popup_body">
<div id="popup_main">
<p class="popup_header">Elenco assenze per ora</p>
<form class="popup_form">
<p style="margin: 10px 0; text-align: center; font-weight: bold; font-style: italic; text-decoration: underline"><?php echo $label ?>, <?php echo $hour['ora'] ?> ora</p>
<div style="margin: 10px auto 0 auto; width: 90%">
<?php 
if (count($abs) == 0 && count($absh) == 0){
?>
<span style="font-weight: bold; font-size: 1.1em">Nessuno studente assente</span>
<?php
}
else {
	if (count($abs) > 0){
?>
<p style="font-weight: bold; text-align: center; margin-bottom: 10px">Alunni assenti</p>
<?php
		foreach ($abs as $a){
?>
<p style="line-height: 5px"><?php echo $a['cognome']." ".$a['nome'] ?></p>
<?php
		}
	}
	if (count($absh) > 0){
?>
<p style="font-weight: bold; text-align: center; margin-bottom: 10px; <?php if (count($abs) > 0): ?>margin-top: 20px<?php endif; ?>">Alunni parzialmente assenti</p>
<?php
		foreach ($absh as $a){
?>
<p style="line-height: 5px"><?php echo $a[1]." ".$a[0] ?> (<?php echo $a[2] ?> minuti)</p>
<?php
		}
	}
}
?>
</div>
</div>
</form>
</body>
</html>
