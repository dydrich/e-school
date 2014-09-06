<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var win;
var win2;
<?php echo $change_subject->getJavascript() ?>

var change_subject = function(id){
	document.location.href="lessons.php?subject="+id+"&q=<?php print $q ?>";
};

var show_absences = function(id){
	$('#iframe').attr("src", "show_hour_absences.php?idh="+id);
	$('#if_pop').dialog({
		autoOpen: true,
		show: {
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
			duration: 400
		},
		modal: true,
		width: 450,
		title: 'Assenze ora',
		open: function(event, ui){

		}
	});
};

var dialogclose = function(){
	$('#if_pop').dialog("close");
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
<div class="group_head">
	<span style="float: left; padding-left: 10px">Materia: <span id="uscita" style="font-weight: normal; "><?php $change_subject->printLink() ?></span></span>
			<span style="font-size: 1.1em">Elenco lezioni<?php print $label ?> (<?php echo $res_lessons->num_rows ?> ore)</span><a href="lessons.php?q=<?php echo $q ?>&order=<?php echo $order ?><?php echo $_group ?>" style="float: right; font-weight: normal; padding-right: 10px "><?php echo $link_label ?></a>
</div>
<div class="outline_line_wrapper">
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/70.png" /><br />Data</div>
	<div style="width: 15%; float: left; position: relative; top: 30%"><img src="../../../images/<?php echo $image ?>" style="margin-left: 8px" onclick="document.location.href='lessons.php?group=<?php echo $group ? "1" : "0" ?>&q=<?php echo $q ?>&order=<?php echo $order_to ?>'" /></div>
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
				print("<tr><td colspan='3' style='height: 20px; vertical-align: middle; font-weight: normal; text-transform: uppercase; font-size: 13m; text-align: center; border-bottom: 1px solid rgba(30, 67, 137, .4); background-color: rgba(30, 67, 137, .2); '>$str_month</td></tr>");
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
<div id="if_pop" style="display: none">
	<iframe id="iframe" src="show_hour_absences.php" style="width: 100%; height: 380px; margin: 0 auto; padding: 0"></iframe>
</div>
</body> 
</html>
