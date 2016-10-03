<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: esiti esame conclusivo primo ciclo</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = 0;

		var set_grade = function(id_voto, alunno, val){
			//alert(id_es);
			var url = "registra_voto_esame.php";
			//alert(url);
			$.ajax({
				type: "POST",
				url: url,
				data: {id_voto: id_voto, alunno: alunno, voto: val},
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
						$("#voto_"+alunno).data("voto", json.id);
						return json.id;
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

		function show_context_menu(offset) {
			if ($('#context_menu').is(":visible")) {
				$('#context_menu').slideUp(300);
				return;
			}
			$('#context_menu').css({'top': offset.top+"px"});
			$('#context_menu').css({'left': offset.left+"px"});
			$('#context_menu').slideDown(500);
			/*
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
			 */
		}

		var set_f = function(id_es, alunno, val){
			//alert(id_es);
			var url = "registra_esito_esame.php";
			//alert(url);
			$.ajax({
				type: "POST",
				url: url,
				data: {id_esito: id_es, alunno: alunno, esito: val},
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
						$("#voto_"+alunno).data("esito", json.id);
						return json.id;
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
			$('.esiti').change(function(){
				id_esito = $(this).data("esito");
				id_alunno = $(this).attr("data-alunno");
				set_f(id_esito, id_alunno, $(this).val());
				//alert($(this).data("esito"));
				if ($(this).val() < 3) {
					$(this).parent().prev("td").removeClass("esito_negativo");
					$(this).parent().prev("td").addClass("esito_positivo");
				}
				else {
					$(this).parent().prev("td").removeClass("esito_positivo");
					$(this).parent().prev("td").addClass("esito_negativo");
				}
			});
			$('.voti').change(function(){
				id_voto = $(this).data("voto");
				id_alunno = $(this).attr("data-alunno");
				id_esito = $(this).data("esito");
				sesso = $(this).data("sesso");
				voto = $(this).val();
				esito = "";
				s_esito = "";
				if (voto > 5) {
					if (sesso == "M") {
						esito = 1;
						s_esito = "Esito positivo";
					}
					else {
						esito = 2;
						s_esito = "Esito positivo";
					}
				}
				else {
					if (sesso == "M") {
						esito = 3;
						s_esito = "Esito negativo";
					}
					else {
						esito = 4;
						s_esito = "Esito negativo";
					}
				}
				set_grade(id_voto, id_alunno, voto);
				set_f(id_esito, id_alunno, esito);
				if (esito < 3) {
					$(this).parent().prev("td").prev("td").removeClass("esito_negativo");
					$(this).parent().prev("td").prev("td").addClass("esito_positivo");
					$(this).parent().prev("td").removeClass("esito_negativo");
					$(this).parent().prev("td").addClass("esito_positivo");
				}
				else {
					$(this).parent().prev("td").prev("td").removeClass("esito_positivo");
					$(this).parent().prev("td").prev("td").addClass("esito_negativo");
					$(this).parent().prev("td").removeClass("esito_positivo");
					$(this).parent().prev("td").addClass("esito_negativo");
				}
				$(this).parent().prev("td").text(s_esito)
			});
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div style="width: 100px; height: 40px; position:relative; top: -15px; margin-left: 905px; margin-bottom: -33px; text-align: right">
		<div style="margin-bottom: -10px" class="rb_button">
			<a href="pdf_esiti_esame.php">
				<img src="../../../images/pdf-32.png" style="padding: 4px 0 0 7px" />
			</a>
		</div>
	</div>
	<table class="registro">
		<thead>
		<tr class="head_tr_no_bg">
			<td style="text-align: center; width: 40%"><span id="ingresso" style=""><?php echo $_SESSION['__classe__']->to_string() ?></span></td>
			<td colspan="2" style="text-align: center; width: 55%">Esiti esame conclusivo del primo ciclo</td>
		</tr>
		<tr class="title_tr">
			<td style="font-weight: bold; padding-left: 12px">Alunno</td>
			<td style="width: 40%; text-align: center; font-weight: bold">Esito</td>
			<td style="width: 20%; text-align: center; font-weight: bold">Voto</td>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($alunni as $al) {
			$positivo = "";
			$string_esito = "";
				if ($al['esito'] != "") {
					$positivo = $esiti_possibili[$al['esito']]['positivo'];
					$string_esito = $esiti_possibili[$al['esito']]['esito'];
				}
			?>
			<tr>
				<td style="padding-left: 8px; font-weight: bold" class="<?php if ($positivo == '1') echo "esito_positivo"; else if ($positivo == '0') echo "esito_negativo" ?>">
					<span style="font-weight: normal"><?php echo $al['cognome'] . " " . substr($al['nome'], 0, 1) ?>.</span>
				</td>
				<td class="_center _bold <?php if ($positivo == '1') echo "esito_positivo"; else if ($positivo == '0') echo "esito_negativo" ?>">
					<?php echo $string_esito ?>
				</td>
				<td class="_center ">
					<select class="voti" data-esito="<?php echo $al['id_esito'] ?>" data-sesso="<?php echo $al['sesso'] ?>" data-voto="<?php echo $al['id_voto'] ?>" data-alunno="<?php echo $al['id_alunno'] ?>" name="voto_<?php echo $al['id_alunno'] ?>" id="voto_<?php echo $al['id_alunno'] ?>" style="width: 85%">
						<option value="0">.</option>
						<?php
						for ($i = 1; $i < 11; $i++) {
							?>
							<option value="<?php echo $i ?>" <?php if ($i == $al['voto']) echo "selected" ?>><?php echo $i ?></option>
						<?php
						}
						?>
						<option value="11" <?php if ($i == $al['voto']) echo "selected" ?>>10 e lode</option>
					</select>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
		<tfoot>
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
	<?php if ($ordine_scuola == 1): ?>
		<p><a href="stampa_scrutini_classe.php?q=<?php echo $q ?>&abs=1"">Scarica riepilogo completo</a></p>
	<?php endif; ?>
	<p><a href="stampa_scrutini_classe.php?q=<?php echo $q ?>&abs=0">Scarica riepilogo voti</a></p>
	<?php if ($q == 2 && ($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == "rbachis")): ?>
		<a href="crea_tabellone.php">Crea il tabellone esiti</a>
	<?php endif; ?>
</div>
</body>
</html>
