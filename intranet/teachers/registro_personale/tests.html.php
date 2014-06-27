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
var new_test = function(){
	win = new Window({className: "mac_os_x", url: "new_test.php",  width:400, height:240, zIndex: 100, resizable: true, title: "Nuova verifica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});	
	win.showCenter(true);
};

<?php echo $change_subject->getJavascript() ?>

function change_subject(id){
	document.location.href="tests.php?subj="+id+"&q=<?php print $q ?>";
}
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
	<span style="font-size: 1.1em">Elenco verifiche <?php print $label ?></span>
	<span style="float: right">Materia: <span id="uscita" style="font-weight: normal; margin-right: 10px; "><?php $change_subject->printLink() ?></span></span>
</div>
<div style="width: 99%; margin: auto; height: 35px; text-align: center; text-transform: uppercase; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
	<div style="width: 25%; float: left; position: relative"><img src="../../../images/70.png" /><br />Data</div>
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/54.png" /><br />Valutata</div>
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/14.png" /><br />Num. alunni</div>
	<div style="width: 10%; float: left; position: relative"><img src="../../../images/35.png" /><br />Media</div>
	<div style="width: 45%; float: left; position: relative"><img src="../../../images/10.png" /><br />Descrizione</div>
</div>
<table class="registro" style="width: 99%; margin: 20px auto 0 auto">
	<thead>
	</thead>
	<tbody>
	<?php 
	while($test = $res_tests->fetch_assoc()){
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
		<td style="width: 25%; text-align: left; padding-left: 20px; font-weight: normal; "><?php print utf8_encode($giorno_str) ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal;"><?php print ($count_alunni > 0) ? "SI" : "NO" ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal;"><?php print $count_alunni ?></td>
		<td style="width: 10%; text-align: center; font-weight: normal;"><?php print $avg ?></td>
		<td style="width: 45%; text-align: center; font-weight: normal;"><a href="test.php?idt=<?php print $test['id_verifica'] ?>" style="font-weight: normal; "><?php echo $test['prova']."::".$test['argomento'] ?></a></td>
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
</body>
</html>