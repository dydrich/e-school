<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="../../../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
<script type="text/javascript">
var win;
var win2;
<?php echo $change_subject->getJavascript() ?>

function change_subject(id){
	document.location.href="lessons.php?subject="+id+"&q=<?php print $q ?>";
}

var show_absences = function(id){
	win2 = new Window({className: "mac_os_x", url: "show_hour_absences.php?idh="+id,  width:400, height:300, zIndex: 100, resizable: true, title: "Elenco assenze", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});	
	win2.showCenter(true);
};

</script>
<style>
table.registro td {
	border: 0px
}
</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<!-- div nascosto, per la scelta della materia -->
<?php $change_subject->toHTML() ?>
<div style="width: 99%; height: 30px; margin: 30px auto 0 auto; text-align: center; font-size: 1.0em; text-transform: uppercase">
	<span style="float: left">Materia: <span id="uscita" style="font-weight: normal; "><?php $change_subject->printLink() ?></span></span>
			<span style="font-size: 1.1em">Elenco lezioni<?php print $label ?> (<?php echo $res_lessons->num_rows ?> ore)</span><a href="lessons.php?q=<?= $q ?>&order=<?= $order ?><?= $_group ?>" style="float: right; font-weight: normal"><?= $link_label ?></a>
</div>
<div style="width: 99%; margin: auto; height: 35px; text-align: center; text-transform: uppercase; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/70.png" /><br />Data</div>
	<div style="width: 15%; float: left; position: relative; top: 30%"><img src="../../../images/<?php echo $image ?>" style="margin-left: 8px" onclick="document.location.href='lessons.php?group=<?= $group ? "1" : "0" ?>&q=<?= $q ?>&order=<?= $order_to ?>'" /></div>
	<div style="width: 75%; float: left; position: relative"><img src="../../../images/35.png" /><br />Argomento</div>
</div>
<table class="registro" style="width: 99%; margin: 20px auto 0 auto">
	<thead>
	</thead>
	<tbody>
	<?php
	$day = "";
	$mese = "";
	while($les = $res_lessons->fetch_assoc()){
		setlocale(LC_TIME, "it_IT.utf8");
		list($y, $m, $d) = explode("-", $les['data']);
		if($group){
			if($mese != $m){
				$str_month = ucfirst(strftime("%B", strtotime($les['data'])));
				print("<tr><td colspan='3' style='height: 20px; vertical-align: middle; font-weight: normal; text-transform: uppercase; font-size: 13m; text-align: center; border-bottom: 1px solid #CCCCCC; background-color: rgba(211, 222, 199, 0.3); '>$str_month</td></tr>");
			}
		}
		$giorno_str = $group ? ucfirst(strftime("%A %d", strtotime($les['data']))) : ucfirst(strftime("%A %d %B", strtotime($les['data'])));
		$print_day = ($day != $les['data']) ? true : false;
	?>
	<tr style="border-bottom: 1px solid #cccccc">
		<td colspan="2" style="width: 25%; text-align: left; padding-left: 20px; font-weight: normal; "><?php if($print_day) print $giorno_str ?></td>
		<td style="width: 75%; text-align: center; font-weight: normal;">
			<span id="lesson_<?php echo $les['id'] ?>" onclick="show_absences(<?php echo $les['id'] ?>)"><?php print utf8_decode(stripslashes($les['argomento'])) ?></span>
		</td>
	</tr>
	<?php
		$day = $les['data'];
		$mese = $m;
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="5" style="height: 35px; "></td>
	</tr>
	<tr style="text-align: center; border-width: 1px 0 1px 0; border-style: solid; border-color: #CCCCCC; height: 40px">
		<td colspan="5">
			<a href="lessons.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="lessons.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
			</a>
			<a href="lessons.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
			</a>
		</td>
	</tr>
	</tfoot>
</table>
</div> 
<?php include "../footer.php" ?>
</body> 
</html>
