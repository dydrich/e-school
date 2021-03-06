<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: scrutini</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = 0;

		function show_menu(el) {
			if($('#menu_div').is(":hidden")) {
				position = $('#ctx_img').offset();
				ftop = position.top + $('#ctx_img').height();
				fleft = position.left - ($('#menu_div').width() - $('#ctx_img').width());
				console.log("top: "+ftop+"\nleft: "+fleft);
				$('#menu_div').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
				$('#menu_div').slideDown(500);
			}
			else {
				$('#menu_div').hide();
			}
		}

		function show_context_menu(el) {
			if($('#context_menu').is(":hidden")) {
				position = getElementPosition(el);
				ftop = position['top'] + $('#'+el).height();
				fleft = position['left'] - 140 + $('#'+el).width();
				console.log("top: "+ftop+"\nleft: "+fleft);
				$('#context_menu').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
				$('#context_menu').show(500);
			}
			else {
				$('#context_menu').hide();
			}
		}

		var _show = function(e, off) {
			if ($('#other_drawer').is(":visible")) {
				$('#other_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#other_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#other_drawer').show('slide', 300);
			return true;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
			$('#imglink').click(function(event){
				event.preventDefault();
				show_menu('imglink');
			});
			$('#menu_div').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_div').hide();
		    });
			$('#context_menu').mouseleave(function(event){
				event.preventDefault();
				$('#context_menu').hide();
			});
		<?php if ($q == 2): ?>
			$('.setstid').click(function(event){
				//alert(this.id);
				event.preventDefault();
				var strs = this.id.split("_");
				stid = strs[1];
				show_context_menu(this.id);
			});
		<?php else: ?>
			$('.setstid').click(function(event){
				//alert(this.id);
				event.preventDefault();
				// do_nothing();
			});
		<?php endif; ?>
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<?php if ($ordine_scuola != 2) { ?>
	<div class="mdtabs">
		<div class="mdtab<?php if(!isset($_REQUEST['view']) && !isset($_REQUEST['modification'])) echo " mdselected_tab" ?>">
			<a href="scrutini.php?q=<?php echo $q ?>"><span>Voti e assenze</span></a>
		</div>
		<div class="mdtab<?php if(isset($_REQUEST['view']) && $_REQUEST['view'] == "grade_only") echo " mdselected_tab" ?>">
			<a href="scrutini.php?q=<?php echo $q ?>&view=grade_only"><span>Solo i voti</span></a>
		</div>
	</div>
<?php
}
?>
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td style="text-align: center"><span id="ingresso" style=""><?php echo $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php echo ($num_colonne - 1) ?>" style="text-align: center">Quadro riassuntivo dell'alunno</td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: <?php echo $first_column_width ?>%; font-weight: bold; padding-left: 2px">Alunno</td>
	<?php 
	foreach($materie as $materia){
	?>
	<td colspan="2" <?php if($materia['id_materia'] == 2 || $materia['id_materia'] == 40 || true === $grades_only) echo ("rowspan='2'") ?> style="width: <?php echo $column_width * 2 ?>%; text-align: center; font-weight: bold"><?php echo strtoupper(substr($materia['materia'], 0, 3)) ?></td>
	<?php 
	}
	?>
</tr>
<tr class="title_tr">
	<?php 
	for($i = 0; $i < $res_materie->num_rows - 1; $i++){
		if(!$grades_only){
	?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weght: bold">V</td>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weght: bold">A</td>
	<?php 
		}
	}
	?>
</tr>
</thead>
<tbody>
<?php

	$student_sum = 0;
	$student_corrected_sum = 0;
	$num_materie = count($materie);
	$st_bckg = "";
	if ($q == 2){
		$sel_idpubblicazione = "SELECT MAX(id_pagella) AS id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()}";
		try{
			$res_idp = $db->executeQuery($sel_idpubblicazione);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		$row = $res_idp->fetch_assoc();
		$idp = $row['id_pagella'];
		$sel_outcome = "SELECT esito FROM rb_pagelle WHERE id_pubblicazione = {$idp} AND id_alunno = {$alunno}";
		$res_outcome = $db->executeQuery($sel_outcome);
		$_outcome = $res_outcome->fetch_assoc();
		$outcome = $_outcome['esito'];
		if ($outcome != "" && $outcome != null){
			$sel_es = "SELECT * FROM rb_esiti WHERE id_esito = {$outcome}";
			$res_es = $db->executeQuery($sel_es);
			$row_es = $res_es->fetch_assoc();
			$es = $row_es['positivo'];
			if ($es == 0){
				$st_bckg = "background-color: rgba(225,11,52,.2);";
			}
			else {
				$st_bckg = "background-color: rgba(30, 67, 137, .2);";
			}
		}
	}
	$background = "background-color: #e8eaec";
?>
<tr>
	<td style="<?php echo $st_bckg ?>width: <?php echo $first_column_width ?>%; padding-left: 8px; font-weight: bold">
		<span style="font-weight: normal"><a href="../../shared/no_js.php" class="setstid" id="st_<?php echo $alunno ?>"><?php echo $_SESSION['__sp_student__']['cognome']." ".substr($_SESSION['__sp_student__']['nome'], 0, 1) ?>.</a></span>
		<span style="float: right; margin-right: 18px; font-weight: bold" id="avg<?php echo $alunno ?>"></span>
	</td>
<?php 
	reset($materie);
	foreach($materie as $materia){
		$sel_voti = "SELECT voto, assenze FROM rb_scrutini WHERE alunno = ".$alunno." AND materia = ".$materia['id_materia']." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = $q";
		//print $sel_voti;
		try{
			$res_voti = $db->executeQuery($sel_voti);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		$dt = $res_voti->fetch_assoc();
		if($dt['voto'] != "" && ($materia['id_materia'] != 26 && $materia['id_materia'] != 30)){
			$student_sum += $dt['voto'];
			if ($dt['voto'] < 6){
				$student_corrected_sum += 6;
			}
			else {
				$student_corrected_sum += $dt['voto'];
			}
		}
		else{
			$num_materie--;
		}
		if($materia['id_materia'] == 2 || $materia['id_materia'] == 40){
?>
	<td style="width: <?php echo $column_width*2 ?>%; text-align: center">
		<?php 
		if (!$readonly){ 

		} 
		else{
			if ($ordine_scuola == 1){
		?>
		<span style="font-weight: bold"><?php echo $dt['voto'] ?></span>
		<?php
			}
			else {
		?>
			<span style="font-weight: bold"><?php if(isset($dt['voto'])) echo $voti_comportamento_primaria[$dt['voto']]['codice'] ?></span>
		<?php
			}
		} 
		?>
	</td>
<?php 
		}
		else{
			if(!$grades_only){
?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold;<?php echo $background ?>"><span class="<?php if($dt['voto'] < 6 && $dt['voto'] > 0) echo("attention") ?>"><?php echo $dt['voto'] ?></span></td>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: normal;"><?php echo $dt['assenze'] ?></td>
<?php 
			}
			else{
?>
	<td style="text-align: center; font-weight: bold;" colspan="2"><span class="<?php if($dt['voto'] < 6 && $dt['voto'] > 0) echo("attention") ?>"><?php echo $dt['voto'] ?></span></td>
<?php
			}
		}
	}
	$student_avg = "0";
	$student_corrected_avg = "0";
	if($num_materie > 0){
		$student_avg = round(($student_sum / $num_materie), 2);
		$student_corrected_avg = round(($student_corrected_sum / $num_materie), 2);
	}
	if ($ordine_scuola == 1):
?>	
	<script>
		$('#avg<?php echo $alunno ?>').html("<?php echo $student_avg; ?> / <?php echo $student_corrected_avg ?>");
		if(<?php echo $student_avg; ?> < <?php echo $_SESSION['__config__']['limite_sufficienza'] ?>)
			$('#avg<?php echo $alunno ?>').addClass("attention");
	</script>
<?php
	endif;
?>
</tr>
</tbody>
<tfoot>
<tr>
	<td colspan="<?php echo $num_colonne ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php echo $num_colonne ?>" style="text-align: center; height: 40px">
		<a href="scrutini.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" /><span>1 Quadrimestre</span>
		</a>
		<a href="scrutini.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" /><span>2 Quadrimestre</span>
		</a>
	</td>
</tr>
</tfoot>
</table>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
    <div style="width: 100%; height: 430px">
        <div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>

        <div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
	    <div class="drawer_link submenu"><a href="medie_voto.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Medie voto</a></div>
	    <div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
        <div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
        <div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
        <div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
        <?php if(is_installed("com")){ ?>
            <div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
        <?php } ?>
    </div>
    <?php if (isset($_SESSION['__sudoer__'])): ?>
        <div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
    <?php endif; ?>
    <div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php if ($q == 2): ?>
<!-- menu contestuale -->
<div id="context_menu" style="position: absolute; width: 240px; height: 120px; display: none">
    <a style="font-weight: normal" href="#" onclick="set_f(17)">Anno non validato</a><br />
<?php
while ($row = $res_out->fetch_assoc()){
?>
    <a style="font-weight: normal" href="#" onclick="set_f(<?php echo $row['id_esito'] ?>)"><?php echo $row['esito'] ?></a><br />
<?php
}
?>
</div>
<!-- fine menu contestuale -->
<?php endif; ?>
</body>
</html>
