<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = 0;
		var ordine_di_scuola = <?php echo $ordine_scuola ?>;
		var ass_ing = new Array;
		<?php
		if ($is_today && $ordine_scuola == 1){
			foreach ($assenze_ingiustificate as $ind => $row){
		?>
		ass_ing[<?php echo $ind ?>] = <?php echo count($row) ?>;
		<?php
			}
		}
		?>

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#context_menu').mouseleave(function(event){
				$('#context_menu').slideUp(200);
			});
			$('#gotoday').datepicker({
				dateFormat: "yy-mm-dd",
				onClose: function(){
					document.location.href = "registro_classe.php?data="+this.value;
				}
			});
			$('#cls_enter').timepicker({
				onClose: function(){
					field = "ingresso";
					updateClassTime(field);
				}
			});
			$('#cls_exit').timepicker({
				onClose: function(){
					field = "uscita";
					updateClassTime(field);
				}
			});
			<?php if ($ordine_scuola == 1): ?>
			$('.student_enter').timepicker({
				onClose: function(){
					strs = this.id.split('_');
					updateStudentTime(strs[1], "ingresso");
				}
			});
			$('.student_exit').timepicker({
				onClose: function(){
					strs = this.id.split('_');
					updateStudentTime(strs[1], "uscita");
				}
			});
			<?php endif; ?>
			$('.st_link').click(function(event){
				var offset = $(this).offset();
				offset.top = offset.top + $(this).height();
				var stid = $(this).attr("data-id");
				show_menu(event, stid, offset);
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			}).css({
				cursor: "pointer"
			});
		});

		var updateClassTime = function(field) {
			var url = "cambia_orari_classe.php";
			if (field == "ingresso"){
				value = $('#cls_enter').val();
			}
			else {
				value = $('#cls_exit').val();
			}
			$.ajax({
				type: "POST",
				url: url,
				data: {field: field, value: value},
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
						if (field == "ingresso"){
							//alert("ingresso");
							$('input.student_enter').each(function(i, elem){
								if ($(elem).val() != "A") {
									$(elem).hide(100);
									$(elem).val(value);
									$(elem).show(400);
								}
							});
						}
						else {
							//alert("ingresso");
							$('input.student_exit').each(function(i, elem){
								if ($(elem).val() != "A") {
									$(elem).hide(100);
									$(elem).val(value);
									$(elem).show(400);
								}
							});
						}
					}
				}
		    });
		};

		var updateStudentTime = function(stid, field) {
			if (field == "ingresso"){
				val = $('#ingresso_'+stid).val();
			}
			else {
				val = $('#uscita_'+stid).val();
			}
			var url = "cambia_orario_alunno.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "change_time", stid: stid, field: field, value: val},
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
				}
		    });
		};

		var absent = function(){
			$('#context_menu').hide(200);
			var url = "cambia_orario_alunno.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "absent", stid: stid},
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
						$('#ingresso_'+stid).hide();
						$('#uscita_'+stid).hide();
						$('#ingresso_'+stid).val('A');
						$('#uscita_'+stid).val('A');
						$('#ingresso_'+stid).show(400);
						$('#uscita_'+stid).show(400);
						<?php if (!$is_today): ?>
						$('#abs_'+stid).hide();
						$('#abs_'+stid).text("Assenza da giustificare");
						$('#abs_'+stid).show(400);
						if ($('#notes_'+stid).text() != ""){
							$('#sep_'+stid).show();
						}
						<?php endif; ?>
					}
				}
		    });
		};

		var present = function(){
			$('#context_menu').hide(200);
			var url = "cambia_orario_alunno.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "present", stid: stid},
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
						$('#ingresso_'+stid).hide();
						$('#uscita_'+stid).hide();
						$('#ingresso_'+stid).val(json.ingresso);
						$('#uscita_'+stid).val(json.uscita);
						$('#ingresso_'+stid).show(400);
						$('#uscita_'+stid).show(400);
						<?php if (!$is_today): ?>
						$('#abs_'+stid).text('');
						$('#sep_'+stid).hide();
						<?php endif; ?>
					}
				}
		    });
		};

		var giustifica = function(id_alunno, assenze){
			giorni_da_giustificare = assenze;
			$('#dialog_'+id_alunno).dialog({
				autoOpen: true,
				show: {
					effect: "fade",
					duration: 500
				},
				hide: {
					effect: "fade",
					duration: 300
				},
				buttons: [{
					text: "Chiudi",
					click: function() {
						$( this ).dialog( "close" );
					}
				}],
				modal: true,
				width: 450,
				title: 'Giustifica assenze',
				open: function(event, ui){

				}
			});
		};

		var firma = function(id){
			document.location.href = 'sign.php?id_reg='+id+'&data=<?php echo $_REQUEST['data'] ?>';
		};

		var show_menu = function(e, _stid, offset){
			if ($('#context_menu').is(":visible")) {
				$('#context_menu').slideUp(300);
				return false;
			}
			$('#context_menu').css({'top': offset.top+"px"});
			$('#context_menu').css({'left': offset.left+"px"});
			$('#context_menu').slideDown(500);
		    stid = _stid;
		    return false;
		};

		var list_notes = function(){
			document.location.href = "dettaglio_note.php?al="+stid;
		};

		var show_note = function(element){
			element.getElementsByTagName("span")[0].style.display = "block";
		};

		var hide_note = function(element){
			element.getElementsByTagName("span")[0].style.display = "none";
		};

		var data_giustificata;
		var giustifica_assenza = function(id_alunno, id_registro) {
			var giorni_da_giustificare = ass_ing[id_alunno];
			data_gustificata = id_registro;
			var url = "giustifica.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {alunno: id_alunno, registro: id_registro},
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
						$('#giust_'+id_alunno+"_"+data_giustificata).hide();
		                giorni_da_giustificare--;
		                ass_ing[id_alunno] = giorni_da_giustificare;
		                if(giorni_da_giustificare == 0){
			                $('#dialog_'+id_alunno).dialog("close");
			                window.setTimeout(function(){
				                document.location.reload();
			                }, 350);
		                }
		                else {
		                    $('#count_abs_'+id_alunno).text(giorni_da_giustificare);
		                }
					}
				}
		    });
			data_giustificata = id_registro;
		};

		var _show = function(e, off) {
			if ($('#classeslist_drawer').is(":visible")) {
				$('#classeslist_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#classeslist_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#classeslist_drawer').show('slide', 300);
			return true;
		};

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<form>
		<div style="top: -20px; margin-left: 925px; margin-bottom: -10px; z-index: 100" class="rb_button">
			<a href="#" onclick="firma('<?php print $_SESSION['registro']['id_reg'] ?>')">
				<img src="../../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
	<table class="registro" style="position: relative; top: -30px">
	<thead>
	<tr class="head_tr_no_bg">
		<td colspan="2" style="text-align: center; ">Orario d'ingresso: <span id="ingresso" style="font-weight: bold"><input type="text" id="cls_enter" name="cls_enter" style="width: 70px; " value="<?php print substr($_SESSION['registro']['ingresso'], 0, 5) ?>" /></span></td>
		<td colspan="2" style="text-align: center; ">Orario d'uscita: <span id="uscita" style="font-weight: bold"><input type="text" id="cls_exit" name="cls_exit" style="width: 70px" value="<?php print substr($_SESSION['registro']['uscita'], 0, 5) ?>" /></span></td>
	</tr>
	<tr class="title_tr">
		<td style="width: 35%; padding-left: 8px">Alunno</td>
		<td style="width: 15%; text-align: center">Entrata</td>
		<td style="width: 15%; text-align: center">Uscita</td>
		<td style="width: 35%; text-align: center">Note</td>
	</tr>
	</thead>
	<tbody>
	<?php
		$idx = 0;
		foreach ($alunni as $k => $al){
			// estrazione di ora ingresso e uscita
			$sel_orario_alunno = "SELECT ingresso, uscita, giustificata FROM rb_reg_alunni WHERE id_alunno = {$k} AND id_registro = ".$_SESSION['registro']['id_reg'];
			$res_orario_alunno = $db->execute($sel_orario_alunno);
			$orario_alunno = $res_orario_alunno->fetch_assoc();
			if($orario_alunno['ingresso'] == "")
				$entrata = "A";
			else
				$entrata = substr($orario_alunno['ingresso'], 0, 5);
			if($orario_alunno['uscita'] == "")
				$uscita = "A";
			else
				$uscita = substr($orario_alunno['uscita'], 0, 5);
			$background = "";
			if($idx%2)
				$background = "background-color: #e8eaec";

			if(!$is_today && !$summer){
				$add_spaces = false;
				if($entrata == "A" && ($orario_alunno['giustificata'] == 0 || $orario_alunno['giustificata'] == ""))
					$add_spaces = true;

				// ricerca di note
				$sel_note = "SELECT COUNT(*) FROM rb_note_disciplinari WHERE alunno = {$k} AND data = '".$_REQUEST['data']."' ORDER BY id_nota ASC";
				$num_note = $db->executeCount($sel_note);
				if($num_note == 1){
					$adj = "nota disciplinare";
				}
				else if($num_note > 1){
					$adj = "note disciplinari";
				}
				$display_sep = "none";
				if ($add_spaces && $num_note > 0){
					$display_sep = "inline";
				}
	?>
	<tr>
		<td class="<?php if($idx == 0) echo("reg_firstrow"); else echo("reg_row"); ?>" style="width: 35%; padding-left: 8px"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
			<a href="#" data-id="<?php echo $k ?>" class="st_link" style="font-weight: normal; color: inherit"><?php print stripslashes($al) ?></a>
		</td>
		<td style="width: 15%; text-align: center;"><input type="text" id="ingresso_<?php print $k ?>" name="ingresso_<?php print $k ?>" class="student_enter" style="width: 30px; text-align: center; color: black; border: 0; margin: auto; font-size: 11px" value="<?php print $entrata ?>" /></td>
		<td style="width: 15%; text-align: center;"><input type="text" id="uscita_<?php print $k ?>" name="uscita_<?php print $k ?>" class="student_exit" style="width: 30px; text-align: center; color: black; border: 0; margin: auto; font-size: 11px" value="<?php print $uscita ?>" /></td>
		<td style="width: 35%; text-align: center; ">
			<?php if ($ordine_scuola == 1): ?>
				<span id="abs_<?php echo $k ?>"><?php if($add_spaces) echo "Assenza da giustificare"; ?></span>

				<span style="display: <?php echo $display_sep ?>; margin: 0 10px 0 10px" id="sep_<?php echo $k ?>">|</span>

				<span id="notes_<?php echo $k ?>" style="color: #161414; font-weight: normal"><?php if($num_note > 0) echo $num_note . " ".$adj ?></span>
			<?php endif; ?>
		</td>
	</tr>
	<?php
			}
			else{
				$add_separator = false;
				$ass_ingiustificate = 0;
				if (isset($assenze_ingiustificate[$k])){
					$ass_ingiustificate = count($assenze_ingiustificate[$k]);
				}
				if ($ass_ingiustificate > 0){
					$add_separator = true;
				}

				// ricerca di note
				$sel_note = "SELECT COUNT(id_nota) FROM rb_note_disciplinari WHERE alunno = {$k} AND anno = {$_SESSION['__current_year__']->get_ID()}";
				$num_note = $db->executeCount($sel_note);

				$display_sep = "none";
				if ($add_separator && $num_note > 0){
					$display_sep = "inline";
				}
	?>
	<tr>
		<td class="<?php if($idx == 0) echo("reg_firstrow"); else echo("reg_row"); ?>" style="width: 35%; padding-left: 8px"><?php if($idx < 9) echo "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?><a href="#" data-id="<?php echo $k ?>" class="st_link" style="font-weight: normal; color: inherit"><?php print stripslashes($al) ?></a></td>
		<td style="width: 15%; text-align: center;"><input type="text" id="ingresso_<?php echo $k ?>" name="ingresso_<?php echo $k ?>" class="student_enter" style="width: 30px; text-align: center; font-size: 11px; font-weight: normal; color: black; background-color: #FFFFFF; border: 0; margin: auto" value="<?php print $entrata ?>" /></td>
		<td style="width: 15%; text-align: center;"><input type="text" id="uscita_<?php echo $k ?>" name="uscita_<?php echo $k ?>" class="student_exit" style="width: 30px; text-align: center; font-size: 11px; font-weight: normal; color: black; background-color: #FFFFFF; border: 0; margin: auto" value="<?php print $uscita ?>" /></td>
		<td class="<?php if($idx == 0) echo("reg_firstrow"); else echo("reg_row"); ?> reg_lastcell" id="notes_<?php echo $k ?>" style="width: 35%; text-align: center">
			<?php if ($ordine_scuola == 1): ?>
			<span id="abs_<?php echo $k ?>" style="color: #373946">
				<?php if($ass_ingiustificate  > 0): ?>
				<span id="count_abs_<?php echo $k ?>" style="color: #373946"><?php if($ass_ingiustificate  > 0) echo $ass_ingiustificate ?></span><a id="abs_link_<?php echo $k ?>" style='text-decoration: none; font-weight: normal' href='#' onclick='javascript: giustifica(<?php echo $k ?>, <?php echo $ass_ingiustificate ?>)'> assenze ingiustificate</a>

				<?php else: ?>
				<span id="count_abs_<?php echo $k ?>" style="color: #373946"></span><a id="abs_link_<?php echo $k ?>" style='text-decoration: none; font-weight: normal' href='#' onclick=''></a>
				<?php endif; ?>
			</span>
			<?php endif; ?>

				<span style="display: <?php echo $display_sep ?>; margin: 0 10px 0 10px" id="sep_<?php echo $k ?>">|</span>

			<?php if($num_note  > 0): ?>
			<span id="notes_<?php echo $k ?>">
				<a style='text-decoration: none; font-weight: normal' href="dettaglio_note.php?al=<?php echo $k ?>"><?php echo $num_note ?> note disciplinari</a>
			</span>
			<?php else: ?>
			<span>
				<a id="notes_<?php echo $k ?>" style='text-decoration: none; font-weight: normal' href="dettaglio_note.php?al=<?php echo $k ?>"></a>
			</span>
			<?php endif; ?>
			<?php if($ass_ingiustificate  > 0): ?>
			<div id="dialog_<?php echo $k ?>" style="display: none">
				<div style="text-align: center; font-weight: bold; padding-bottom: 25px">Elenco assenze da giustificare<br />Alunno <?php echo $alunni[$k] ?></div>
				<div style="width: 90%; text-align: left; margin: auto;">
				<?php
				setlocale(LC_TIME, "it_IT");
				//$idx = 0;
				foreach($assenze_ingiustificate[$k] as $dt => $ass){
					$giorno_str = utf8_encode(strftime("%A", strtotime($dt)));
				?>
					<div id="giust_<?php echo $k ?>_<?php echo $ass ?>" style="height: 20px; padding-bottom: 0px; vertical-align: middle; border-bottom: 1px solid #CCCCCC; padding-left: 20px"><?php print substr($giorno_str, 0, 3)." ". format_date($dt, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
						<span style="float: right; margin-right: 45px;">
							<a href="#" onclick="giustifica_assenza(<?php echo $k ?>, <?php print $ass ?>)" style="font-weight: normal; text-decoration: underline;">Giustifica</a>
						</span>
					</div>
				<?php
					//$idx++;
				}
				?>
				</div>
				</div>
				<?php endif; ?>
		</td>
	</tr>
	<?php
			}
	?>

	<?php
			$idx++;
	}
	?>
	<tfoot>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<?php
	$forward_link = "registro_classe.php?data=".$data_forward;
	?>
	<tr class="nav_tr">
		<td colspan="1" style="text-align: left; border-right: none">
		<?php if($data_back){ ?>
			<a href="registro_classe.php?data=<?php echo $data_back ?>" style="float: left; width: 48%" >&lt;&lt; Giorno precedente</a>
		<?php } else{ ?>
			<span style="float: left; width: 48%" >&lt;&lt; Giorno precedente</span>
		<?php } ?>
		</td>
		<td colspan="2" class="_center" style="border-right: none; border-left:none;">
			<input type="text" id="gotoday" name="gotoday" style="width: 90px; text-align: center; font-size: 11px; font-family: Georgia; margin: auto" value="Giorno..." />
		</td>
		<td style="text-align: right; border-left: none">
			<?php if(!$data_forward){ ?><span style="float: right">Giorno successivo &gt;&gt;</span><?php } else{ ?>
			<a href="<?php echo $forward_link ?>" style="float: right">Giorno successivo &gt;&gt;</a><?php } ?>
		</td>
	</tr>
	</tfoot>
	</table>
	</form>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu">
			<a href="stats.php"><img src="../../../images/18.png" style="margin-right: 10px; position: relative; top: 5%"/>Statistiche</a>
		</div>
		<div class="drawer_link submenu separator">
			<a href="notes.php"><img src="../../../images/26.png" style="margin-right: 10px; position: relative; top: 5%"/>Note</a>
		</div>
		<div class="drawer_link submenu"><a href="registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
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
<div id="classeslist_drawer" class="drawer" style="height: <?php echo (36 * (count($_SESSION['__user__']->getClasses()) - 1)) ?>px; display: none; position: absolute">
	<?php
	foreach ($_SESSION['__user__']->getClasses() as $cl) {
		if ($cl['id_classe'] != $_SESSION['__classe__']->get_ID()) {
			?>
			<div class="drawer_link ">
				<a href="<?php echo getFileName() ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>&data=<?php echo $_REQUEST['data'] ?>">
					<img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
					Classe <?php echo $cl['classe'] ?>
				</a>
			</div>
		<?php
		}
	}
	?>
</div>
<!-- menu contestuale -->
<div id="context_menu" style="position: absolute; width: 160px; height: 60px; display: none; ">
	<a style="font-weight: normal" href="#" onclick="absent()">Segna come assente</a><br />
	<a style="font-weight: normal" href="#" onclick="present()">Segna come presente</a><br />
	<a style="font-weight: normal" href="#" onclick="list_notes()">Note</a><br />
</div>
<!-- fine menu contestuale -->
</body>
</html>
