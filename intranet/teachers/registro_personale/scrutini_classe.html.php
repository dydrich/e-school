<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: scrutini</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = 0;
		var upd_grade = function(sel, alunno, subj){
			var url = "upd_grade.php";
			//alert(subj);
			$.ajax({
				type: "POST",
				url: url,
				data: {grade: sel.value, alunno: alunno, q: <?php echo $q ?>, subj: subj},
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						<?php if ($ordine_scuola == 1): ?>
						$('#avg'+alunno).text(json.avg+"/"+json.avg2);
						if(parseInt(json.avg) < <?php echo $_SESSION['__config__']['limite_sufficienza'] ?>)
							$('#avg'+alunno).addClass("attention");
						else
							$('#avg'+alunno).removeClass("attention");
						<?php endif; ?>
					}
				}
			});
		};

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

		var set_f = function(id_es){
			var url = "set_outcome.php";
			//alert(url);
			$.ajax({
				type: "POST",
				url: url,
				data: {outcome: id_es, alunno: stid},
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						if (json.positivo == 0){
							$('#st_'+stid).parentNode.parentNode.css({backgroundColor: 'rgba(225,11,52,.2)'});
						}
						else{
							$('#st_'+stid).parentNode.parentNode.css({backgroundColor: 'rgba(30, 67, 137, .2)'});
						}
					}
					show_context_menu('');
				}
			});
		};

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

		var choose_print = function(){
			$('#confirm_print').dialog({
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
				title: 'Scarica riepiloghi',
				open: function(event, ui){

				}
			});
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
			<a href="scrutini_classe.php?q=<?php echo $q ?>"><span>Voti e assenze</span></a>
		</div>
		<div class="mdtab<?php if(isset($_REQUEST['view']) && $_REQUEST['view'] == "grade_only") echo " mdselected_tab" ?>">
			<a href="scrutini_classe.php?q=<?php echo $q ?>&view=grade_only"><span>Solo i voti</span></a>
		</div>
		<?php if (!$readonly): ?>
		<div class="mdtab<?php if(isset($_REQUEST['modification']) && $_REQUEST['modification'] == 1) echo " mdselected_tab" ?>">
			<a href="<?php echo $link ?>"><span>Modifica</span></a>
		</div>
		<?php endif; ?>
	</div>
<?php
}
?>
<div style="width: 100px; height: 40px; position:relative; top: -15px; margin-left: 905px; margin-bottom: -33px; text-align: right">
	<div style="margin-bottom: -10px" class="rb_button">
		<a href="#" onclick="choose_print()">
			<img src="../../../images/pdf-32.png" style="padding: 4px 0 0 7px" />
		</a>
	</div>
<?php if ($ordine_scuola == 2 && !$readonly) { ?>
	<div style="right: 80px; top: -30px" class="rb_button">
		<a href="scrutini_classe.php?q=<?php echo $q ?><?php echo $modification_params ?>">
			<img src="../../../images/39.png" style="margin: 10px 10px 0 0"/>
		</a>
	</div>
<?php
}
?>
</div>
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td style="text-align: center"><span id="ingresso" style=""><?php echo $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php echo ($num_colonne - 1) ?>" style="text-align: center">Quadro riassuntivo della classe</td>
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
$idx = 0;
while($al = $res_alunni->fetch_assoc()){
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
		$sel_outcome = "SELECT esito FROM rb_pagelle WHERE id_pubblicazione = {$idp} AND id_alunno = {$al['id_alunno']}";
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
	<td style="<?php echo $st_bckg ?>width: <?php echo $first_column_width ?>%; padding-left: 8px; font-weight: bold"><?php if($idx < 9) echo "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<span style="font-weight: normal"><a href="../../shared/no_js.php" class="setstid" id="st_<?php echo $al['id_alunno'] ?>"><?php echo $al['cognome']." ".substr($al['nome'], 0, 1) ?>.</a></span>
		<span style="float: right; margin-right: 18px; font-weight: bold" id="avg<?php echo $al['id_alunno'] ?>"></span>	
	</td>
<?php 
	reset($materie);
	foreach($materie as $materia){
		$sel_voti = "SELECT voto, assenze FROM rb_scrutini WHERE alunno = ".$al['id_alunno']." AND materia = ".$materia['id_materia']." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = $q";
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
		?>
		<select name="sel_<?php echo $al['id_alunno'] ?>" style="width: 45px; height: 15px; font-size: 11px" onchange="upd_grade(this, <?php echo $al['id_alunno'] ?>, <?php echo $materia['id_materia'] ?>)">
			<option value="0">NC</option>
			<?php 
			if ($ordine_scuola == 1){
				for($i = 10; $i > 0; $i--){ 
			?>
			<option value="<?php echo $i ?>" <?php if($dt['voto'] == $i) echo "selected" ?>><?php echo $i ?></option>
			<?php 
				} 
			}else {
				foreach ($voti_comportamento_primaria as $k => $val){		
			?>
			<option value="<?php echo $k ?>" <?php if($dt['voto'] == $k) echo "selected" ?>><?php echo $val['nome'] ?></option>
			<?php
				}
			}
			?>
		</select>
		<?php 
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
			if(!$modification){
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
			else{
?>
	<td colspan="2">
	<select name="sel_<?php echo $al['id_alunno'] ?>_<?php echo $materia['id_materia'] ?>" style="width: 45px; height: 15px; font-size: 11px" onchange="upd_grade(this, <?php echo $al['id_alunno'] ?>, <?php echo $materia['id_materia'] ?>)">
			<option value="0">NC</option>
			<?php for($i = 10; $i > 0; $i--){ ?>
			<option value="<?php echo $i ?>" <?php if($dt['voto'] == $i) echo "selected" ?>><?php echo $i ?></option>
			<?php } ?>
		</select>
	</td>
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
		$('#avg<?php echo $al['id_alunno'] ?>').html("<?php echo $student_avg; ?> / <?php echo $student_corrected_avg ?>");
		if(<?php echo $student_avg; ?> < <?php echo $_SESSION['__config__']['limite_sufficienza'] ?>)
			$('#avg<?php echo $al['id_alunno'] ?>').addClass("attention");
	</script>
<?php
	endif;
?>
</tr>
<?php
	$idx++;
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="<?php echo $num_colonne ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php echo $num_colonne ?>" style="text-align: center; height: 40px">
		<a href="scrutini_classe.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" /><span>1 Quadrimestre</span>
		</a>
		<a href="scrutini_classe.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
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
		<div class="drawer_link submenu">
			<a href="scrutini.php?q=<?php echo $_q ?>"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
		</div>
		<?php if ($q == 2): ?>
			<div class="drawer_link submenu separator">
				<a href="confronta_scrutini.php"><img src="../../../images/46.png" style="margin-right: 10px; position: relative; top: 5%"/>Confronta scrutini</a>
			</div>
		<?php endif; ?>
		<?php if ($ordine_scuola == 2): ?>
		<div class="drawer_link submenu separator">
			<a href="parametri_pagella.php?q=<?php echo $q ?>"><img src="../../../images/73.png" style="margin-right: 10px; position: relative; top: 5%"/>Livelli di maturazione</a>
		</div>
		<?php endif; ?>
		<div class="drawer_link submenu"><a href="index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
		<?php if($is_teacher_in_this_class && $_SESSION['__user__']->getSubject() != 27 && $_SESSION['__user__']->getSubject() != 44) { ?>
		<div class="drawer_link submenu separator">
			<a href="#" id="showsub"><img src="../../../images/68.png" style="margin-right: 10px; position: relative; top: 5%"/>Altro</a>
		</div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
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
<div id="other_drawer" class="drawer" style="height: 144px; display: none; position: absolute">
	<?php if (!isset($_REQUEST['__goals__']) && (isset($_SESSION['__user_config__']['registro_obiettivi']) && (1 == $_SESSION['__user_config__']['registro_obiettivi'][0]))): ?>
		<div class="drawer_link ">
			<a href="index.php?q=<?php echo $q ?>&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1"><img src="../../../images/31.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro per obiettivi</a>
		</div>
	<?php endif; ?>
	<?php if ($ordine_scuola == 1): ?>
		<div class="drawer_link">
			<a href="absences.php"><img src="../../../images/52.png" style="margin-right: 10px; position: relative; top: 5%"/>Assenze</a>
		</div>
	<?php endif; ?>
	<div class="drawer_link">
		<a href="tests.php"><img src="../../../images/79.png" style="margin-right: 10px; position: relative; top: 5%"/>Verifiche</a>
	</div>
	<div class="drawer_link">
		<a href="lessons.php"><img src="../../../images/62.png" style="margin-right: 10px; position: relative; top: 5%"/>Lezioni</a>
	</div>
	<?php
	}
	else { ?>
		<div class="drawer_link separator">
			<a href="scrutini_classe.php?q=<?php echo $_q ?>"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
		</div>
	<?php } ?>
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
<div id="confirm_print" style="display: none">
	<p><a href="stampa_scrutini_classe.php?q=<?php echo $q ?>&abs=1"">Scarica riepilogo completo</a></p>
	<p><a href="stampa_scrutini_classe.php?q=<?php echo $q ?>&abs=0">Scarica riepilogo voti</a></p>
	<?php if ($q == 2 && ($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == "rbachis")): ?>
	<a href="crea_tabellone.php">Crea il tabellone esiti</a>
	<?php endif; ?>
</div>
</body>
</html>
