<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">

</script>
<style>
tbody tr:hover {
	background-color: #eceff1;
}
</style>
</head>  
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
<div class="group_head">
	Compiti assegnati (<a href="<?php echo $link ?>" style="font-weight: normal"><?php echo $label ?></a>)
</div>
<div class="outline_line_wrapper">
	<div style="width: 22%; float: left; position: relative; top: 25%"><span style="padding-left: 15px">Per il...</span></div>
	<div style="width: 48%; float: left; position: relative; top: 25%">Compito</div>
	<div style="width: 10%; float: left; position: relative; top: 25%">Materia</div>
	<div style="width: 10%; float: left; position: relative; top: 25%">Assegnato</div>
	<div style="width: 8%; float: left; position: relative; top: 25%">Alunni</div>
</div>
<table style="width: 95%; margin: 10px auto 0 auto">
<?php 
if($res_act->num_rows < 1){
?>
	<tr>
    	<td colspan="5" style="height: 150px; font-weight: bold; text-transform: uppercase; text-align: center">Nessun compito assegnato</td> 
    </tr>
<?php 
}
else{
?>

<?php 
	$idx = 1;
	$bc = "";
	$data = "";
	while($dt = $res_dates->fetch_assoc()){
		if($idx > 1)
			print('<tr><td colspan="5" style="text-align: center; border-width: 0px 1px 1px 1px; border-style: solid; font-weight: bold; font-size: 12px;height: 10px; border-color: #B0BEC5;"></td></tr>');
		$sel_hw = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio = '".$dt['data_inizio']."' AND rb_impegni.tipo = 2 $teacher ORDER BY data_inizio DESC";
		//print $sel_hw;
		$res_hw = $db->execute($sel_hw);
		$rows = $res_hw->num_rows;
		$ct = 1;
		list($di, $oi) = explode(" ", $dt['data_inizio']);
		setlocale(LC_ALL, "it_IT.utf8");
		$giorno_str = strftime("%A", strtotime($di));
?>
	<tr>
		<td style="width: 20%; text-align: center; font-weight: normal; border-width: 1px 1px 1px 1px; border-style: solid; border-color: #B0BEC5" rowspan="<?php echo $rows ?>"><?php print $giorno_str." ". format_date($di, SQL_DATE_STYLE, IT_DATE_STYLE, "/")?></td>
<?php 
		while($hw = $res_hw->fetch_assoc()){
			$bc = "";
			if(($idx%2) != 0)
				$bc = "background-color: #e8eaec; ";
			list($da, $oa) = explode(" ", $hw['data_assegnazione']);
			$mod = 1;
			if($_SESSION['__user__']->getUid() != $hw['docente'])
				$mod = 0;
?>
		<?php if($ct > 1) print("<tr>") ?>
		<td style="width: 50%; text-align: center; font-weight: normal; border-width: 1px 1px 1px 1px; border-style: solid; border-color: rgba(30, 67, 137, .4)"><?php if ($hw['docente'] == $_SESSION['__user__']->getUid()): ?><a style="color: #303030; font-weight: normal; text-decoration: none" href="dettaglio_compito.php?t=<?php print $hw['id_impegno'] ?>"><?php endif; ?><?php print $hw['descrizione'] ?><?php if ($hw['docente'] == $_SESSION['__user__']->getUid()): ?></a><?php endif; ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal; border-width: 1px 1px 1px 1px; border-style: solid; border-color: rgba(30, 67, 137, .4)"><?php print $hw['mat'] ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal; border-width: 1px 1px 1px 1px; border-style: solid; border-color: rgba(30, 67, 137, .4)"><?php print format_date($da, SQL_DATE_STYLE, IT_DATE_STYLE, "/")?></td>
		<td style="width: 10%; text-align: center; font-weight: normal; border-width: 1px 1px 1px 1px; border-style: solid; border-color: rgba(30, 67, 137, .4)">Tutti</td>
	</tr>

<?php 
			$ct++;
			$idx++;
		}
	}
?>
<?php 
}
?>
</table>
<div style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 40px; text-align: right">
<a href="dettaglio_compito.php?t=0" style="text-transform: uppercase; margin-right: 20px; text-decoration: none">
	<img src="../../../images/39.png" />
	Nuovo compito</a>
</div> 
</div>
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
