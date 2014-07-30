<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../reg.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript">
</script>
<style>
tbody tr:hover {
	background-color: rgba(211, 222, 199, 0.6);
}
</style>
</head> 
</head> 
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
<div style="width: 95%; height: 30px; margin: 30px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
	Attivit&agrave; programmate (<a href="<?php print $link ?>" style="font-weight: normal"><?php print $label ?></a>)
</div>
<div style="width: 95%; margin: auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
	<div style="width: 50%; float: left; position: relative; top: 30%">Attivit&agrave;</div>
	<div style="width: 10%; float: left; position: relative; top: 30%">Assegnata</div>
	<div style="width: 10%; float: left; position: relative; top: 30%">Materia</div>
	<div style="width: 15%; float: left; position: relative; top: 30%">Inizia</div>
	<div style="width: 15%; float: left; position: relative; top: 30%">Termina</div>
</div>
<table style="width: 95%; margin: 20px auto 0 auto">
<?php 
if($res_act->num_rows < 1){
?>
	<tr>
    	<td colspan="5" style="height: 150px; font-weight: bold; text-transform: uppercase; text-align: center">Nessuna attivit&agrave; presente</td> 
    </tr>
<?php 
}
else{
?>
<?php 
	$idx = 1;
	while($act = $res_act->fetch_assoc()){
		$bc = "";
		if(($idx%2) != 0) {
			$bc = "background-color: #e8eaec; ";
		}
		list($da, $oa) = explode(" ", $act['data_assegnazione']);
		list($di, $oi) = explode(" ", $act['data_inizio']);
		list($df, $of) = explode(" ", $act['data_fine']);
		$desc = $act['descrizione'];
		if($act['note'] != "") {
			$desc .= " (".$act['note'].")";
		}
		$mod = true;
		if($_SESSION['__user__']->getUid() != $act['docente']) {
			$mod = false;
		}
			
?>
	<tr>
		<td style="width: 50%; text-align: left; font-weight: normal; border: 1px solid #c0c0c0; padding-left: 5px;"><?php if ($mod): ?><a style="font-weight: normal" href="dettaglio_attivita.php?t=<?php print $act['id_impegno'] ?>"><?php endif; ?><?php print $desc ?><?php if ($mod): ?></a><?php endif; ?></td>
		<td style="width: 10%; text-align: left; font-weight: normal; padding-left: 5px; border: 1px solid #c0c0c0;"><?php print format_date($da, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal; border: 1px solid #c0c0c0;"><?php print $act['mat'] ?></td>
		<td style="width: 15%; text-align: center; font-weight: normal; border: 1px solid #c0c0c0;"><?php print format_date($di, SQL_DATE_STYLE, IT_DATE_STYLE, "/")." ".substr($oi, 0, 5) ?></td>
		<td style="width: 15%; text-align: center; font-weight: normal; border: 1px solid #c0c0c0;"><?php print format_date($df, SQL_DATE_STYLE, IT_DATE_STYLE, "/")." ".substr($of, 0, 5) ?></td>
	</tr>
<?php 
		$idx++;
	}
?>
<?php 
}
?>
</table>
<div style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 40px; text-align: right">
<a href="dettaglio_attivita.php?t=0" style="text-transform: uppercase; margin-right: 20px; text-decoration: none">
	<img src="../../../images/39.png" />
	Nuova attivit&agrave;</a>
</div> 
</div> 
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
