<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/documents.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var upd_grade = function(sel, al, idv){
	$.ajax({
		type: "POST",
		url: 'test_manager.php',
		data:  {voto: $('#'+sel.id).val(), alunno: al, verifica: <?php print $_REQUEST['idt'] ?>, id_voto: idv, do: "update_grade"},
		dataType: 'json',
		error: function(data, status, errore) {
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				$('#num_st').text(json.count);
				$('#avg').text(json.media);
				//alert($('voto'+al).readAttribute('onchange'));
				if(sel.value == 0){
					$('#voto'+al).change(function(){
						upd_grade(this, al, 0);
					});
				}
				else{
					if(idv == 0){
						$('#voto'+al).change(function(){
							upd_grade(this, al, json.idv)
						});
					}
				}
			}
		}
	});
};

var delete_test = function(delete_grades){
	if (delete_grades) {
		delt = "delete_all";
	}
	else {
		delt = "delete_test";
	}

	$.ajax({
		type: "POST",
		url: 'test_manager.php',
		data:  {id_verifica: <?php print $_REQUEST['idt'] ?>, do: delt},
		dataType: 'json',
		error: function(data, status, errore) {
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				alert(json.message);
				document.location.href = "tests.php";
			}
		}
	});
};

var dialogclose = function(){
	$('#test').dialog("close");
};

var update_test = function(){
	//win = new Window({className: "mac_os_x", url: "new_test.php?test=<?php print $_REQUEST['idt'] ?>&referer=test",  width:400, height:240, zIndex: 100, resizable: true, title: "Gestione verifica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	//win.showCenter(true);
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
		title: 'Modifica verifica',
		open: function(event, ui){

		}
	});
}

function choose_del(){
	//win = new Window({className: "mac_os_x", url: "new_test.php?test=<?php print $_REQUEST['idt'] ?>&referer=test",  width:400, height:240, zIndex: 100, resizable: true, title: "Gestione verifica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	//win.showCenter(true);
	$('#confirm_del').dialog({
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
		width: 350,
		height: 150,
		title: 'Modifica verifica',
		open: function(event, ui){

		}
	});
}

</script>
<style>
td { border: inherit; }
table.registro td {
	border: 0
}
</style>
</head> 
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<div class="group_head">Dettaglio verifica di <?php echo $test->getSubject()->getDescription() ?> del <span id="date_label"><?php echo format_date(substr($test->getTestDate(), 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></span></div>
<fieldset style="width: 95%; margin: auto; border-radius: 10px; background-color: rgba(222, 222, 222, 0.1)">
<legend style="margin-left: 15px; font-weight: bold">Dati verifica</legend>
<table style="border-collapse: collapse; width: 90%; margin-left: auto; margin-right: auto; margin-top: 10px; margin-bottom: 10px">
	<tr style="height: 25px; border-bottom: 1px solid rgba(30, 67, 137, .8)">
		<td style="width: 10%; font-weight: bold">Data e ora</td>
		<td style="width: 20%; text-align: right;" id="datetm"><?php echo $test->testDateToString() ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Alunni</td>
		<td style="width: 20%; font-weight: bold; text-align: right;" id="num_st"><?php print $test->getEvaluatedStudents() ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Media voto</td>
		<td style="width: 20%; font-weight: bold; text-align: right;" id="avg"><?php echo $test->getAverage() ?></td>
		
	</tr>
	<tr style="height: 25px; border-bottom: 1px solid rgba(30, 67, 137, .8)">
		<td style="width: 10%; font-weight: bold">Prova</td>
		<td style="width: 20%; text-align: right;" id="desc"><?php print $test->getDescription() ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Tipo</td>
		<td style="width: 20%; text-align: right;" id="tp"><?php echo $prove[$test->getType()] ?></td>
		<td style="width: 2%"></td>
		<td style="width: 10%; font-weight: bold">Note</td>
		<td style="width: 20%; text-align: right;" id="ann"><?php print $test->getAnnotation() ?></td>
	</tr>
	<tr style="height: 25px; border-bottom: 1px solid rgba(30, 67, 137, .8)">
		<td style="width: 10%; font-weight: bold">Argomento</td>
		<td colspan="7" id="top"><?php print $test->getTopic() ?></td>
	</tr>
	<tr style="height: 25px; border-bottom: 1px solid rgba(30, 67, 137, .8)">
		<td style="width: 10%; font-weight: bold">Obiettivi</td>
		<td colspan="7"><?php echo $string_obj ?></td>
	</tr>
</table>
<div style="width: 90%; text-align: right; margin-right: auto; margin-left: auto; margin-bottom: 20px">
	<a href="#" onclick="update_test()" style="padding-right: 10px; text-transform: uppercase; text-decoration: none">Modifica dati</a>|
	<a href="test_goals.php?idv=<?php echo $test->getId() ?>" style="padding-left: 10px; padding-right: 10px; text-transform: uppercase; text-decoration: none">Obiettivi</a>|
	<a href="#" onclick="choose_del()" style="padding-left: 10px; text-transform: uppercase; text-decoration: none">Cancella verifica</a>
</div>
</fieldset>
<table class="registro" style="width: 98%; margin-left: auto; margin-right: auto; margin-top: 20px" id="det_table">
	<thead>
	<tr>
		<td colspan="6" style="text-align: center; text-decoration: underline; text-transform: uppercase; font-weight: bold; font-size: 1em; padding-bottom: 15px">Valutazioni</td>	
	</tr>
	</thead>
	<tbody>
	<tr style="border-bottom: 1px solid #cccccc">
	<?php
	$student_in_a_row = 3;
	foreach($test->getStudents() as $x => $alunno){
	?>
		<td style="width: 22%; text-align: left; font-weight: normal; "><?php print $alunno['name'] ?></td>
		<td style="width: 11%; text-align: center; font-weight: normal;">
			<select id="voto<?php print $alunno['stid'] ?>" style="font-size: 11px; " onchange="upd_grade(this, <?php echo $alunno['stid'] ?>, <?php print $alunno['grade']['gid'] ?>)">
				<option value="0" >Assente</option>
				<?php
				if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
					foreach ($voti_religione as $k => $g){
				?>
				<option value='<?php echo $k ?>' <?php if($k == $alunno['grade']['grade']) print "selected='selected'" ?>><?php echo $g ?></option>
				<?php
					}
				}
				else{
					for($x = 100; $x > 9; $x -= 5){
						$v = $x / 10;
				?>
				<option value="<?php print $v ?>" <?php if($v == $alunno['grade']['grade']) print "selected='selected'" ?>><?php print $v ?></option>
				<?php 
					} 
				}
				?>
			</select>
		</td>
	<?php 
		$student_in_a_row--;
		if($student_in_a_row < 1){
			$student_in_a_row = 3;
	?>
	</tr>
	<tr style="border-bottom: 1px solid #cccccc">
	<?php 
		}
	}
	$tds = 3 - (count($test->getStudents()) % 3);
	for($i = 0; $i < $tds; $i++){
	?>
	<td></td>
	<td></td>
	<?php 
	}
	?>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="6" style="height: 40px; text-align: right"><a href="tests.php" style="text-transform: uppercase; text-decoration: none"><img src="../../../images/back.png" style="margin-right: 8px; position: relative; top: 5px" />Torna alle verifiche</a></td>
	</tr>
	</tfoot>
</table>
</div> 
<?php include "../footer.php" ?>
<div id="test" style="display: none">
	<iframe src="new_test.php?test=<?php echo $_REQUEST['idt'] ?>" style="width: 100%; margin: auto; border: 0; height: 290px"></iframe>
</div>
<div id="confirm_del" style="display: none">
	<p><a href="#" onclick="delete_test(1)">Cancella la verifica e i voti associati</a></p>
	<a href="#" onclick="delete_test(0)">Cancella la verifica e mantieni i voti</a>
</div>
</body> 
</html>
