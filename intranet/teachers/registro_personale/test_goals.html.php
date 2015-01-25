<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Obiettivi didattici verifica</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var save_data = function(){
			$.ajax({
				type: "POST",
				url: 'test_manager.php',
				data: $('#myform').serialize(true),
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						show_error(json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#not1').text(json.message);
						$('#not1').dialog({
							autoOpen: false,
							show: {
								effect: "appear",
								duration: 500
							},
							hide: {
								effect: "slide",
								duration: 300
							},
							buttons: [{
								text: "Chiudi",
								click: function() {
									$( '#not1' ).dialog( "close" );
								}
							}],
							modal: true,
							width: 450,
							open: function(event, ui){

							}
						});
						$('#not1').dialog('open');
					}
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

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#save_button').click(function(event){
				event.preventDefault();
				save_data();
			});
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
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div style="top: -20px; margin-left: 35px; margin-bottom: -10px" class="rb_button">
		<a href="test.php?idt=<?php echo $_REQUEST['idv'] ?>">
			<img src="../../../images/47bis.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
<form id="myform">
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td style="width: 45%; text-align: center; border-right: 0"><span id="ingresso" style="font-weight: bold; ">Obiettivi verifica</span></td> 
	<td style="width: 55%; text-align: center; border-left: 0"><span id="media" style="font-weight: bold; "><?php echo strtoupper($test->getSubject()->getDescription()) ?>, <?php echo $q ?> quadrimestre</span>
	<div id="not1"></div>
	</td>
</tr>
<tr style="">
	<td style="width: 45%; text-align: left">
	<fieldset style="width: 90%; margin: 20px auto 20px auto; border-radius: 8px">
		<legend style="font-weight: bold">Dettaglio verifica</legend>
		<table style="width: 90%; margin: auto">
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Data</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo format_date(substr($test->getTestDate(), 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Tipo</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $prove[$test->getType()] ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Argomento</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $test->getTopic() ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Note</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $test->getAnnotation() ?></td>
			</tr>				
		</table>
	</fieldset>
	</td> 
	<td style="width: 55%; text-align: left">
		<table style="width: 90%; margin: auto; border: 0; border-collapse: collapse">
			<tr style="border: 0; height: 35px">
				<td style="width: 80%; font-weight: bold; border-width: 0 0 1px 0">Obiettivo</td>
				<td style="width: 80%; font-weight: bold; border-width: 0 0 1px 0; text-align: right"></td>
			</tr>
			<?php 
			foreach ($goals as $row){
				$color = "";
				if (isset($row['idpadre']) && $row['idpadre'] == ""){
					$color = "font-weight: bold";
				}
				?>
					<tr style="border: 0">
						<td style="width: 70%; border-width: 0 0 1px 0; <?php echo $color ?>"><?php echo $row['nome'] ?></td>
						<td style="width: 30%; border-width: 0 0 1px 0; text-align: right">
						<input type="checkbox" id="goal_<?php echo $row['id'] ?>" name="goals[]" value="<?php echo $row['id'] ?>" <?php if (in_array($row['id'], $test->getLearningObjectives())) echo "checked" ?> />
						</td>
					</tr>
			<?php
				if (isset($row['children'])){
					foreach ($row['children'] as $child){
						$color = "";
			?>
					<tr style="border: 0">
						<td style="width: 70%; border-width: 0 0 1px 0; <?php echo $color ?>"><?php echo $child['nome'] ?></td>
						<td style="width: 30%; border-width: 0 0 1px 0; text-align: right">
						<input type="checkbox" id="goal_<?php echo $child['id'] ?>" name="goals[]" value="<?php echo $child['id'] ?>" <?php if (in_array($row['id'], $test->getLearningObjectives())) echo "checked" ?> />
						</td>
					</tr>
			<?php 
					}
				}
			}
			?>
		</table>
	</td>
</tr>
</thead>
<tfoot>
	<tr style="height: 30px">
		<td colspan="2" style="text-align: right">
			<a href="#" id="save_button" style="text-transform: uppercase; text-decoration: none; margin-left: 10px; margin-right: 20px">Salva</a>
		</td>
	</tr>
</tfoot>
</table>
<input type="hidden" id="id_verifica" name="id_verifica" value="<?php echo $_REQUEST['idv'] ?>" />
<input type="hidden" id="do" name="do" value="save_los" />
</form>
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
</body>
</html>
