<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: verifica</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
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

		var choose_del = function(){
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

		$(function () {
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
		});

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
	<div style="top: -20px; margin-left: 35px; margin-bottom: -10px" class="rb_button">
		<a href="tests.php">
			<img src="../../../images/47bis.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
<fieldset style="width: 95%; margin: auto; border-radius: 10px; background-color: rgba(222, 222, 222, 0.1)">
<legend style="margin-left: 15px; font-weight: bold">Dati verifica</legend>
<table style="border-collapse: collapse; width: 90%; margin-left: auto; margin-right: auto; margin-top: 10px; margin-bottom: 10px">
	<tr style="height: 25px; border-bottom: 1px solid rgba(30, 67, 137, .8)">
		<td style="width: 10%; font-weight: bold">Data</td>
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
</table>
</div> 
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
		<?php if(count($_SESSION['__subjects__']) > 1){ ?>
			<div class="drawer_link submenu">
				<a href="summary.php"><img src="../../../images/10.png" style="margin-right: 10px; position: relative; top: 5%"/>Riepilogo</a>
			</div>
		<?php
		}
		if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == 'rbachis') { ?>
			<div class="drawer_link submenu">
				<a href="dettaglio_medie.php"><img src="../../../images/9.png" style="margin-right: 10px; position: relative; top: 5%"/>Dettaglio classe</a>
			</div>
		<?php
		}
		?>
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
<div id="other_drawer" class="drawer" style="height: 180px; display: none; position: absolute">
	<?php if (!isset($_REQUEST['__goals__']) && (isset($_SESSION['__user_config__']['registro_obiettivi']) && (1 == $_SESSION['__user_config__']['registro_obiettivi'][0]))): ?>
		<div class="drawer_link ">
			<a href="index.php?subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1"><img src="../../../images/31.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro per obiettivi</a>
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
	<div class="drawer_link separator">
		<a href="scrutini.php?q=<?php echo $_q ?>"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
	</div>
	<?php
	}
	else { ?>
		<div class="drawer_link separator">
			<a href="scrutini_classe.php?q=<?php echo $_q ?>"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
		</div>
	<?php } ?>
</div>
<div id="test" style="display: none">
	<iframe src="new_test.php?test=<?php echo $_REQUEST['idt'] ?>" style="width: 100%; margin: auto; border: 0; height: 290px"></iframe>
</div>
<div id="confirm_del" style="display: none">
	<p><a href="#" onclick="delete_test(1)">Cancella la verifica e i voti associati</a></p>
	<a href="#" onclick="delete_test(0)">Cancella la verifica e mantieni i voti</a>
</div>
</body> 
</html>
