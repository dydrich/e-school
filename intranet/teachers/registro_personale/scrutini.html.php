<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di classe</title>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
	var stid = 0;
	<?php echo $change_subject->getJavascript() ?>
	function change_subject(id){
		document.location.href="scrutini.php?subject="+id+"&q=<?php print $q ?>";
	}

	var alunni = new Array();
	<?php
	while($r = $res_dati->fetch_assoc()){
	?>
	alunni.push(<?php echo$r['alunno'] ?>);
	<?php
	}
	?>
	$(function(){
		load_jalert();
		for(var i = 0; i < alunni.length; i++){
			alunno = alunni[i];
			//alert(alunno);
			var on_arch = $('#abstd_'+alunno).html();
			$('#grade_'+alunno).load(
				'get_grade_absences.php',
				{
					req: 'grade',
					alunno: alunno,
					q: <?php echo $q ?>
				}
			);
			$('#abs_'+alunno).load(
				'get_grade_absences.php',
				{
					req: "absences",
					alunno: alunno,
					tot: 0,
					q: <?php echo $q ?>
				}
			);
			if(on_arch == 0) {
				$('#abstd_'+alunno).load(
					'get_grade_absences.php',
					{
						req: "absences",
						alunno: alunno,
						tot: 1,
						q: <?php echo $q ?>
					}
				);
			}
			/*
			var req = new Ajax.Updater('grade_'+alunno, 'get_grade_absences.php', { method: 'post', parameters: {req: "grade", alunno: alunno, q: <?php echo $q ?>} });
			var req2 = new Ajax.Updater('abs_'+alunno, 'get_grade_absences.php', { method: 'post', parameters: {req: "absences", alunno: alunno, tot: 0, q: <?php echo $q ?>} });
			if(on_arch == 0)
				var req3 = new Ajax.Updater('abstd_'+alunno, 'get_grade_absences.php', { method: 'post', parameters: {req: "absences", alunno: alunno, tot: 1, q: <?php echo $q ?>} });
			*/
			$('#grade_'+alunno).show(1500);
			<?php if ($ordine_scuola == 1) : ?>
			$('#abs_'+alunno).show(1500)
			<?php endif; ?>
		}
		upd_avg();
		$('#imglink').click(function(event){
			event.preventDefault();
			show_menu('imglink');
		});
		$('#menu_div').mouseleave(function(event){
			event.preventDefault();
			$('#menu_div').hide();
		});
	});

	var upd_avg = function(){
		var url = "get_avg.php";
		$('#avg').load(
			url,
			{
				param: 'avg',
				q: <?php echo $q ?>
			}
		);
		$('#avg2').load(
			url,
			{
				param: 'grd',
				q: <?php echo $q ?>
			}
		);
		//var req = new Ajax.Updater('avg', url, { method: 'post', parameters: {param: 'avg', q: <?php echo $q ?>} });
		//var req2 = new Ajax.Updater('avg2', url, { method: 'post', parameters: {param: 'grd', q: <?php echo $q ?>} });

	};

	var upd_grade = function(sel, alunno){
		var url = "upd_grade.php";
		//alert(url);
		$.ajax({
			type: "POST",
			url: url,
			data: {grade: sel.value, alunno: alunno, q: <?php echo $q ?>},
			dataType: 'json',
			error: function() {
				j_alert("error", "Errore di trasmissione dei dati");
			},
			succes: function() {

			},
			complete: function(data){
				r = data.responseText;
				if(r == "null"){
					return false;
				}
				var json = $.parseJSON(r);
				if (json.status == "kosql"){
					alert(json.message);
					console.log(json.dbg_message);
				}
				else {
					upd_avg();
				}
			}
		});
	};

	var show_menu = function(el) {
		if($('#menu_div').is(":hidden")) {
			position = getElementPosition(el);
			ftop = position['top'] + $('#'+el).height();
			fleft = position['left'] - 140 + $('#'+el).width();
			console.log("top: "+ftop+"\nleft: "+fleft);
			$('#menu_div').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
			$('#menu_div').show(500);
		}
		else {
			$('#menu_div').hide();
		}
	};
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<!-- div nascosto, per la scelta della materia -->
<?php $change_subject->toHTML() ?>
<form action="student.php" method="post">
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="2" style="text-align: center; font-weight: bold"><?php print $_SESSION['__current_year__']->to_string() ?><?php print $label ?></td>
	<td colspan="1" style="text-align: right; ">
		<a href="../shared/no_js.php" id="imglink" style="">
			<img src="../../../images/19.png" id="ctx_img" style="margin: 0 10px 4px 0; opacity: 0.5; vertical-align: bottom" />
		</a>
	</td>
</tr>
<tr class="head_tr_no_bg">
	<td colspan="1" style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="2" style="text-align: center; ">Materia: <span id="uscita" style="font-weight: bold; "><?php $change_subject->printLink() ?></span></td>
</tr>
<tr class="title_tr">
	<td style="width: 50%; font-weight: bold; padding-left: 8px">Alunno</td>
	<td style="width: 25%; text-align: center; font-weight: bold">Voto (media voto)</td>
	<td style="width: 25%; text-align: center; font-weight: bold">Assenze</td>
</tr>
</thead>
<tbody>
<?php 
$idx = 0;
$res_dati->data_seek(0);
while($al = $res_dati->fetch_assoc()){
	$background = "";
	if($idx%2)
		$background = "background-color: #e8eaec";
?>
<tr>
	<td style="width: 40%; padding-left: 8px; font-weight: bold;"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<span style="font-weight: normal"><?php print $al['cognome']." ".$al['nome']?></span>
	</td>
	<td style="width: 10%; text-align: center; font-weight: normal;">
	<?php 
	if (!$readonly){
	?>
		<select name="sel_<?php echo $al['alunno'] ?>" style="width: 75px; height: 15px; font-size: 11px" onchange="upd_grade(this, <?php echo $al['alunno'] ?>)">
			<option value="0">NC</option>
			<?php 
			if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
			?>
			<option value='10' <?php if($al['voto'] == 10) print "selected" ?>>Ottimo</option>
			<option value='9' <?php if($al['voto'] == 9) print "selected" ?>>Distinto</option>
			<option value='8' <?php if($al['voto'] == 8) print "selected" ?>>Buono</option>
			<option value='6' <?php if($al['voto'] == 6) print "selected" ?>>Sufficiente</option>
			<option value='4' <?php if($al['voto'] == 4) print "selected" ?>>Insufficiente</option>
			<?php 
			}
			else{
				for($i = 10; $i > 0; $i--){ 
			?>
			<option value="<?php echo $i ?>" <?php if($al['voto'] == $i) print "selected" ?>><?php echo $i ?></option>
			<?php 
				} 
			}
			?>
		</select>
	<?php 
	}
	else {
		if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
	?>
		<span><?php echo $voti_religione[RBUtilities::convertReligionGrade($al['voto'])] ?></span>
	<?php
		}
		else {
	?>
		<span><?php echo $al['voto'] ?></span>
	<?php
		}
	}
	?>
		<span id="grade_<?php echo $al['alunno'] ?>" style="margin-left: 15px; display: none"></span>
	</td>
	<td style="width: 10%; text-align: center; font-weight: normal">
		<span id="abstd_<?php echo $al['alunno'] ?>" style="<?php if ($ordine_scuola == 1) : ?>font-weight: bold;<?php endif; ?> font-size: 1.1em"><?php if ($ordine_scuola == 1) echo $al['assenze']; else echo "ND" ?></span>
		<span id="abs_<?php echo $al['alunno'] ?>" style="margin-left: 15px; display: none"></span>
	</td>
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="3" style="height: 15px"></td>
</tr>
<tr style="font-weight: bold; height: 30px; background-color: rgba(30, 67, 137, .3)">
	<td style="padding-left: 10px">Media classe</td>
	<td style="text-align: center">
		<span id="avg" style="padding-right: 10px"></span>
		<span id="avg2" style="font-weight: normal"></span>
	</td>
	<td></td>
</tr>
<tr>
	<td colspan="3" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="3" style="text-align: center; height: 40px">
			<a href="scrutini.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="scrutini.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
			</a>
		<!-- <a href="index.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=0">Totale</a> -->
	</td>
</tr>
</tfoot>
</table>
</form>
</div>
<?php include "../footer.php" ?>
<div id="menu_div" class="page_menu" style="width: 190px; height: 120px; position: absolute; padding: 10px 0 10px 0px; display: none">
	<?php if(count($_SESSION['__subjects__']) > 1){ ?>
		<a href="riepilogo_scrutini.php?q=<?php echo $q ?>" style="padding-left: 10px; line-height: 16px">Riepilogo</a><br />
	<?php
	}
	if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) || ($_SESSION['__user__']->isAdministrator()) ){
	?>
		<a href="scrutini_classe.php?q=<?php echo $q ?>" style="padding-left: 10px; line-height: 16px">Dettaglio classe</a><br />
	<?php
	}
	if ($q == 2){
	?>
		<a href="confronta_scrutini.php" style="padding-left: 10px; line-height: 16px">Confronta scrutini</a><br />
	<?php
	}
	?>
</div>
</body>
</html>
