<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Attivita</title>
<link rel="stylesheet" href="../../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "menu_sostegno.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Attivit&agrave; svolte</div><a href="dettaglio_attivita.php?id=0" style="float: right; margin-right: 40px" class="standard_link">Nuova attivit&agrave;</a>
		<table style="width: 80%; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
<?php
if ($res_attivita->num_rows < 1){
?>
			<tr>
				<td colspan="2" style="text-align: center; font-weight: bold; height: 75px">
					Nessuna attivit&agrave; presente
				</td> 
			</tr>
<?php
}
else {
	while ($row = $res_attivita->fetch_assoc()){
?>
			<tr class="manager_row_small">
				<td style="width: 20%"><a href="dettaglio_attivita.php?id=<?php echo $row['id'] ?>"><?php echo format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td>
				<td style="width: 80%"><?php echo stripslashes($row['attivita']) ?></td> 
			</tr>
<?php
	}
}
?>
			<tr>
				<td colspan="2">&nbsp;
					<input type="hidden" id="area" name="area" value="nucleo" />
					<input type="hidden" id="idd" name="idd" value="<?php echo $idd ?>" />
				</td>				
			</tr>
		</table>
		<div style="width: 90%; height: 20px; font-weight: normal; padding:10px; display: block; text-align: center; margin: 0 auto 0 auto; border-bottom: 1px solid rgb(211, 222, 199);">
<?php 
for ($z = 0; $z <= $max_m; $z++){		
?>
	<a href="attivita.php?m=<?php echo $num_mesi_scuola[$z] ?>" style="margin: 0 5px 0 5px; text-decoration: none"><?php echo $mesi_scuola[$z] ?></a>
	<?php if ($z < $max_m){ ?>|<?php } ?>
<?php 
	}
?>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
