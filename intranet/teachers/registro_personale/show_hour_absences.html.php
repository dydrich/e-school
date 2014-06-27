<!DOCTYPE html>
<html>
<head>
<title>Registro personale</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe_popup.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="../../../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript">

</script>
<style>
* {font-size: 11px}
</style>
</head>
<body>
<div id="main">
<p style='text-align: center; padding-top: 5px; font-weight: bold; font-size: 1.2em' id='titolo'>Elenco assenze per ora</p>
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
<p style="font-weight: bold; text-align: center; margin-bottom: 15px">Alunni assenti</p>
<?php
		foreach ($abs as $a){
?>
<p><?php echo $a['cognome']." ".$a['nome'] ?></p>
<?php
		}
	}
	if (count($absh) > 0){
?>
<p style="font-weight: bold; text-align: center; margin-bottom: 15px; <?php if (count($abs) > 0): ?>margin-top: 20px<?php endif; ?>">Alunni parzialmente assenti</p>
<?php
		foreach ($absh as $a){
?>
<p><?php echo $a[1]." ".$a[0] ?> (<?php echo $a[2] ?> minuti)</p>
<?php
		}
	}
}
?>
</div>
<div style='width: 94%; text-align: right; margin-top: 20px'>
<input type='button' value='Chiudi' style='width: 50px; font-size: 11px; padding: 2px' id='invia' onclick="parent.win2.close()" />
</div>
</div>
</body>
</html>