<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/documents/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">

var new_test = function(){
	$('#test').dialog({
		autoOpen: true,
		show: {
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
			duration: 300
		},
		modal: true,
		width: 550,
		height: 350,
		title: 'Nuova verifica',
		open: function(event, ui){

		}
	});
};

var dialogclose = function(){
	$('#test').dialog("close");
};

<?php echo $change_subject->getJavascript() ?>

function change_subject(id){
	document.location.href="tests.php?subj="+id+"&q=<?php echo $q ?>";
}

$(function(){
	$('.test_link').click(function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		if (strs[2] == 0) {
			alert("Non hai i permessi necessari per modificare la verifica");
			return false;
		}
		document.location.href = "test.php?idt="+strs[1];
	});
});

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
	<span style="font-size: 1.1em">Elenco verifiche <?php echo $label ?></span>
	<span style="float: right">Materia: <span id="uscita" style="font-weight: normal; margin-right: 10px; "><?php $change_subject->printLink() ?></span></span>
</div>
<div class="outline_line_wrapper">
	<div style="width: 25%; float: left; position: relative"><img src="../../../images/70.png" /><br />Data</div>
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/54.png" /><br />Valutata</div>
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/14.png" /><br />Num. alunni</div>
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/35.png" /><br />Media</div>
	<div style="width: 45%; float: left; position: relative"><img src="../../../images/10.png" /><br />Descrizione</div>
</div>
<table class="registro" style="width: 99%; margin: 20px auto 0 auto">
	<thead>
	</thead>
	<tbody id="tbody">
	<?php 
	while($test = $res_tests->fetch_assoc()){
		$can_modify = 1;
		if ($test['id_docente'] != $_SESSION['__user__']->getUid()) {
			$can_modify = 0;
		}
		$giorno_str = strftime("%A %d %B", strtotime($test['data_verifica']));
		$sel_alunni = "SELECT COUNT(alunno) FROM rb_voti WHERE id_verifica = ".$test['id_verifica'];
		$count_alunni = $db->executeCount($sel_alunni);
		$avg = "-";
		if($count_alunni > 0){
			$sel_avg = "SELECT AVG(voto) FROM rb_voti WHERE id_verifica = ".$test['id_verifica'];
			$avg = round($db->executeCount($sel_avg), 2);
			if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
				$avg = $voti_religione[round($avg)];
			}
		}
	?>
	<tr style="border-width: 1px 0 1px 0; border-style: solid; border-color: #CCCCCC">
		<td style="width: 25%; text-align: left; padding-left: 20px; font-weight: normal; "><?php print $giorno_str ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal;"><?php print ($count_alunni > 0) ? "SI" : "NO" ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal;"><?php print $count_alunni ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal;"><?php print $avg ?></td>
		<td style="width: 45%; text-align: center; font-weight: normal;">
			<a id="test_<?php echo $test['id_verifica'] ?>_<?php echo $can_modify ?>" href="#" class="test_link" style="font-weight: normal; ">
				<?php echo $test['prova']."::".$test['argomento'] ?>
			</a>
		</td>
	</tr>
	<?php 
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="5" style="height: 35px; "></td>
	</tr>
	<tr style="text-align: center; border-width: 1px 0 1px 0; border-style: solid; border-color: #CCCCCC; height: 40px">
		<td colspan="5">
			<a href="tests.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="tests.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
			</a>
			<a href="tests.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
			</a>
		</td>
	</tr>
	<tr style="text-align: right; height: 40px; border-top: 1px solid #CCCCCC">
		<td colspan="5" style="padding-right: 30px">			
			<a href="#" style="text-transform: uppercase; text-decoration: none" onclick="new_test()"><img src="../../../images/39.png" />Nuova verifica</a>
		</td>
	</tr>
	</tfoot>
</table>
</div>
<?php include "../footer.php" ?>
<div id="test" style="display: none">
	<iframe src="new_test.php" style="width: 100%; margin: auto; border: 0; height: 290px"></iframe>
</div>
</body>
</html>
