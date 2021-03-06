<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: note</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var action = "";
		var note_id = 0;

		var count_notes = <?php echo $res_note->num_rows ?>;

		var filter = function() {
			$('#div_tipinota').dialog({
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
				width: 250,
				title: 'Tipo nota',
				open: function(event, ui){

				}
			});
		};

		var new_note = function(){
			$('#titolo_nota').text("Nuova nota");
			$('#action').val("new");
			$('#id_nota').val(0);
			$('#ndate').val('<?php echo date("d/m/Y") ?>');
			$('#pop_note').dialog({
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
				width: 450,
				title: 'Nuova nota',
				open: function(event, ui){

				}
			});
		};

		var update_note = function(id_note, can_modify_annotation){
			if (can_modify_annotation == 0) {
				j_alert("error", "Non hai i permessi per modificare questa nota");
				return false;
			}
			$('#titolo_nota').text("Modifica nota");
			$('#del_button').show();
			$.ajax({
				type: "POST",
				url: "note_manager.php",
				data:  {
					action: 'get',
					id_nota: id_note,
					q: <?php echo $q ?>
				},
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {
					j_alert("alert", "ok");
				},
				complete: function(data, status){
					r = data.responseText;
					var json = $.parseJSON(r);
					if(json.status == "kosql"){
						j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
						return;
					}
					else {
						$('#ndate').val(json.note.data);
						$('#ntype').val(json.note.tipo);
						$('#desc').val(json.note.note);
					}
				}
			});
			$('#action').val("update");
			$('#id_nota').val(id_note);
			$('#pop_note').dialog({
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
				width: 450,
				title: 'Modifica nota',
				open: function(event, ui){

				}
			});
		};

		var register_note = function(){
			if ($('#ndate').val() == "") {
				j_alert("error", "Data obbligatoria");
				return false;
			}
			var url = "note_manager.php";
			ndate = $('#ndate').val();
			ntype = $('#ntype option:selected').text();
			desc = $('#desc').val()
			note_id = $('#id_nota').val();
			$.ajax({
				type: "POST",
				url: url,
				data:  $('#testform').serialize(true),
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {
					j_alert("alert", "ok");
				},
				complete: function(data, status){
					r = data.responseText;
					var json = $.parseJSON(r);
					if(json.status == "kosql"){
						j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
						return;
					}
					else {
						if ($('#action').val() == 'new'){
							if (count_notes == 0) {
								$('#norecords').hide(400);
							}
							count_notes++;
							note_id = json.id;
							var tr = document.createElement("tr");
							tr.setAttribute("id", "tr_"+json.id);
							var td1 = document.createElement("td");
							td1.setAttribute("style", "width: 20%; text-align: center");
							var _a = document.createElement("a");
							_a.setAttribute("href", "#");
							_a.setAttribute("id", "dn_"+note_id);
							td1.appendChild(_a);
							tr.appendChild(td1);

							var td2 = document.createElement("td");
							td2.setAttribute("style", "width: 30%; text-align: center");
							var _a = document.createElement("a");
							_a.setAttribute("href", "#");
							_a.setAttribute("id", "tn_"+note_id);
							td2.appendChild(_a);
							tr.appendChild(td2);

							var td3 = document.createElement("td");
							td3.setAttribute("style", "width: 50%; text-align: center");
							var _a = document.createElement("a");
							_a.setAttribute("href", "#");
							_a.setAttribute("id", "nn_"+note_id);
							td3.appendChild(_a);
							tr.appendChild(td3);

							$('#tbody').prepend(tr);

							$('#dn_'+note_id).click(function(event){
								//alert(this.id);
								update_note(note_id);
							});
							$('#tn_'+note_id).click(function(event){
								//alert(this.id);
								update_note(note_id);
							});
							$('#nn_'+note_id).click(function(event){
								//alert(this.id);
								update_note(note_id);
							});

							$('#dn_'+note_id).css({fontWeight: "normal", color: "#303030"});
							$('#tn_'+note_id).css({fontWeight: "normal", color: "#303030"});
							$('#nn_'+note_id).css({fontWeight: "normal", color: "#303030"});

							$('#dn_'+note_id).addClass("note_link");
							$('#tn_'+note_id).addClass("note_link");
							$('#nn_'+note_id).addClass("note_link");

						}

						$('#dn_'+note_id).text(ndate);
						$('#tn_'+note_id).text(ntype);
						$('#nn_'+note_id).text(desc);

						$('#pop_note').dialog("close");
					}
				}
			});
		};

		var del_note = function(){
			count_notes--;
			note_id = $('#id_nota').val();
            $('#pop_note').dialog("close");
            $('#confirm').fadeOut(100);
			var url = "note_manager.php";
			$.ajax({
				type: "POST",
				url: "note_manager.php",
				data:  {action: "delete", id_nota: note_id},
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {

				},
				complete: function(data, status){
					r = data.responseText;
					var json = $.parseJSON(r);
					if(json.status == "kosql"){
						j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
						return;
					}
					else {
						$('#tr_'+note_id).hide();
						if (count_notes == 0) {
							$('#tbody').prepend('<tr id="norecords"><td colspan="3" style="height: 50px; text-align: center; font-weight: bold">Nessuna annotazione presente</td></tr>');
						}
					}
					$('#pop_note').dialog("close");
                    $('#overlay').hide();
				}
			});
		};

		var show_subdrawer = function(e, off) {
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

		var change_subject = function(id){
			document.location.href="student_notes.php?subject="+id+"&q=<?php echo $q ?>&stid=<?php echo $student_id ?>";
		};

		$(function(){
			load_jalert();
			setOverlayEvent();

			$('.note_link').click(function(event){
				//alert(this.id);
				var strs = this.id.split("_");
				update_note(strs[1], strs[2]);
			});
			$('#ndate').datepicker({
				dateFormat: "dd/mm/yy",
				altFormat: "dd/mm/yy"
			});
			$('#del_button').click(function(event){
				event.preventDefault();
				j_alert("confirm", "Eliminare la nota?");
			});

			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				show_subdrawer(event, off);
			});
			$('#okbutton').on('click', function (event) {
				event.preventDefault();
				del_note();
			});
		});

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<?php
	$label_subject = "";
	if (count($_SESSION['__subjects__']) > 1) {
		?>
		<div class="mdtabs">
			<?php
			foreach ($_SESSION['__subjects__'] as $mat) {
				if (isset($_SESSION['__materia__']) && $_SESSION['__materia__'] == $mat['id']) {
					$label_subject = "::".$mat['mat'];
				}
				?>
				<div class="mdtab<?php if (isset($_SESSION['__materia__']) && $_SESSION['__materia__'] == $mat['id']) echo " mdselected_tab" ?>">
					<a href="#" onclick="change_subject(<?php echo $mat['id'] ?>)"><span><?php echo $mat['mat'] ?></span></a>
				</div>
			<?php
			}
			?>
		</div>
	<?php
	}

	?>
<?php 
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
	<form>
		<div style="top: -8px; margin-left: 825px; margin-bottom: -39px" class="rb_button">
			<a href="#" onclick="filter()">
				<img src="../../../images/69.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div style="top: -8px; margin-left: 895px; margin-bottom: -26px" class="rb_button">
			<a href="#" onclick="new_note(<?php print $alunno['id_alunno'] ?>, <?php print $_SESSION['__materia__'] ?>)">
				<img src="../../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>

<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td colspan="2" style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $alunno['cognome']." ".$alunno['nome'] ?></span></td>
	<td>Materia: <?php print $desc_materia ?></td>
</tr>
<tr class="title_tr"> 
	<td style="width: 20%; text-align: center"><a href="student_notes.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>&order=data" style="font-weight: bold; color: #000000">Data</a></td>
	<td style="width: 30%; text-align: center"><a href="student_notes.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>&order=tipo" style="font-weight: bold; color: #000000">Tipo nota</a></td>
	<td style="width: 50%; text-align: center"><span style="font-weight: bold; ">Commento</span></td>   
</tr>
</thead>
<tbody id="tbody">
<?php
if($res_note->num_rows < 1){
?>
<tr id="norecords">
	<td colspan="3" style="height: 50px; text-align: center; font-weight: bold">Nessuna annotazione presente</td>
</tr>	
<?php 	
}
$background = "";
$index = 1;
$array_voti = array();
while($row = $res_note->fetch_assoc()){
	$can_modify_annotation = 1;
	if ($_SESSION['__user__']->getUid() != $row['docente']) {
		$can_modify_annotation = 0;
	}
	if($index % 2)
		$background = "background-color: #e8eaec";
	else
		$background = "";
?>
<tr id="tr_<?php echo $row['id_nota'] ?>">
	<td style="width: 20%; text-align: center"><a href="#" id="dn_<?php echo $row['id_nota'] ?>_<?php echo $can_modify_annotation ?>" class="note_link" style="font-weight: normal; color: #303030"><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td>
	<td style="width: 30%; text-align: center"><a href="#" id="tn_<?php echo $row['id_nota'] ?>_<?php echo $can_modify_annotation ?>" class="note_link" style="font-weight: normal; color: #303030"><?php print $row['tipo_nota'] ?></a></td>
	<td style="width: 50%; text-align: center"><a href="#" id="nn_<?php echo $row['id_nota'] ?>_<?php echo $can_modify_annotation ?>" class="note_link" style="font-weight: normal; color: #303030"><?php print $row['note'] ?></a></td>
</tr>
<?php 
	$index++;
}

$previous = get_sibling($_SESSION['students'], $student_id, PREVIOUS);
$next = get_sibling($_SESSION['students'], $student_id, NEXT);
if($previous == INDEX_OUT_OF_BOUND){
	$link_p = "#";
	$text_p = "";
}
else{
	$link_p = "student_notes.php?stid=".$previous['id']."&q=$q";
	$text_p = $previous['value'];
}
if($next == INDEX_OUT_OF_BOUND){
	$link_n = "#";
	$text_n = "";
}
else{
	$link_n = "student_notes.php?stid=".$next['id']."&q=$q";
	$text_n = $next['value'];
}
?>
<tr style="height: 30px">
	<td colspan="2" style="text-align: left"><a href="<?php echo $link_p ?>" style="margin-left: 30px; font-weight: normal; text-decoration: none">&lt;&lt; <?php echo $text_p ?></a></td>
	<td colspan="1" style="text-align: right"><a href="<?php echo $link_n ?>" style="margin-right: 30px; font-weight: normal; text-decoration: none"><?php echo $text_n ?> &gt;&gt;</a></td>
</tr>
</tbody>
<tfoot>
<tr>
	<td colspan="3" style="">&nbsp;</td>
</tr>
</tfoot>
</table>
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
<!-- tipi nota -->
    <div id="div_tipinota" style="display: none">
	    <p>
		    <a style="font-weight: normal" class="material_link" href="student_notes.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>&order=data">Tutte le note</a>
	    </p>
    <?php
    while($t = $res_tipi->fetch_assoc()){
    ?>
    	<p>
		    <a style="font-weight: normal" class="material_link" href="student_notes.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>&order=data&tipo=<?php echo $t['id_tiponota'] ?>"><?php echo $t['descrizione'] ?></a>
	    </p>
    <?php } ?>
    </div>
<!-- tipi nota -->
<!-- popup nota -->
<div id="pop_note" style="display: none">
	<form id='testform' method='post' onsubmit="_submit()">
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
			<tr>
				<td style="width: 25%; font-weight: bold">Tipo nota *</td>
				<td style="width: 75%; " colspan="3">
					<select id="ntype" name="ntype" style="font-size: 11px; border: 1px solid gray; width: 100%">
						<?php
						$res_tipi->data_seek(0);
						while($t = $res_tipi->fetch_assoc()){
							?>
							<option value="<?php echo $t['id_tiponota'] ?>"><?php echo $t['descrizione'] ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 25%; font-weight: bold">Data *</td>
				<td style="width: 75%; font-weight: normal" colspan="3">
					<input type="hidden" name="action" id="action" value="" />
					<input type="hidden" name="id_nota" id="id_nota" value="" />
					<input type="hidden" name="stid" id="stid" value="<?php echo $student_id ?>" />
					<input type="text" style="font-size: 11px; border: 1px solid gray; width: 99%" id="ndate" name="ndate" readonly="readonly" value="" />
				</td>
			</tr>
			<tr>
				<td style="width: 25%; font-weight: bold">Note </td>
				<td style="width: 75%; " colspan="3">
					<textarea style="width: 100%; height: 40px; font-size: 11px; border: 1px solid gray" id="desc" name="desc"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top: 20px; text-align: right;">
					<input type="button" id="del_button" value="Elimina" style="width: 70px; padding: 2px; display: none" />
					<input type="button" id="manage_link" onclick="register_note()" value="Registra" style="width: 70px; padding: 2px" />
				</td>
			</tr>
			<tr>
				<td colspan="4" style="height: 10px"></td>
			</tr>
		</table>
	</form>
</div>
</body>
</html>
