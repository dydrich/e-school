<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Note disciplinari di classe </title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var notes_count = <?php echo $res_note->num_rows ?>;
		var filter = function(e) {
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
				width: 300,
				title: 'Tipo nota',
				open: function(e, ui){

				}
			});
		};

		var note_manager = function(nid){
			if (nid == 0) {
				$('#iframe').attr("src", "new_note.php?nc="+notes_count);
				_label = "Nuova nota disciplinare";
			}
			else {
				$('#iframe').attr("src", "new_note.php?id_nota="+nid+"&action=update&nc="+notes_count);
				_label = "Nota disciplinare";
			}

			$('#nota').dialog({
				autoOpen: true,
				show: {
					effect: "fade",
					duration: 500
				},
				hide: {
					effect: "fade",
					duration: 300
				},
				modal: true,
				width: 450,
				title: _label,
				open: function(event, ui){

				}
			});
		};

		var dialogclose = function(){
			$('#nota').dialog("close");
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.note_link').click(function(event){
				//alert(this.id);
				event.preventDefault();
				permission = $(this).attr("data-permission");
				if (permission == 0) {
					j_alert("error", "Non hai i permessi per modificare la nota");
					return false;
				}
				note_manager($(this).attr("data-id"));
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				show_classlist(event, off);
			}).css({
				cursor: "pointer"
			});
		});

		var show_classlist = function(e, off) {
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
	<style>
		.ui-dialog .ui-dialog-content {
			padding: 0
		}
	</style>
</head>
<body <?php if(isset($msg) && $msg != "") print("onload='alert(\"$msg\")'") ?>>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div style="top: -8px; margin-left: 855px; margin-bottom: -39px" class="rb_button">
		<a href="#" onclick="filter(event)">
			<img src="../../../images/69.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
	<div style="top: -8px; margin-left: 925px; margin-bottom: -26px" class="rb_button">
		<a href="#" onclick="note_manager(0)">
			<img src="../../../images/39.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
<form>
<?php 
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td style="width: 10%; text-align: center; "><a href="notes.php?q=<?php echo $q ?>&orderby=data" style="font-weight: bold; color: #000000">Data</a></td>
	<td style="width: 25%; text-align: center; "><a href="notes.php?q=<?php echo $q ?>&orderby=tipo" style="font-weight: bold; ; color: #000000">Tipo nota</a></td>
	<td style="width: 20%; text-align: center; "><a href="notes.php?q=<?php echo $q ?>&orderby=docente" style="font-weight: bold; ; color: #000000">Docente</a></td>
	<td style="width: 45%; text-align: center; "><span style="font-weight: bold; ; color: #000000">Commento</span></td>
</tr>
</thead>
<tbody id="tbody">
<?php
if($res_note->num_rows < 1){
?>
<tr id="nonotes_row">
	<td colspan="4" style="height: 50px; text-align: center; font-weight: bold">Nessuna annotazione presente</td>
</tr>	
<?php 	
}
$background = "";
$index = 1;
while($row = $res_note->fetch_assoc()){
	
?>
<tr id="row<?php echo $row['id_nota'] ?>">
	<td style="width: 10%; text-align: center; "><a id="datlink<?php echo $row['id_nota'] ?>" data-id="<?php echo $row['id_nota'] ?>" data-permission="<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal"><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></a></td>
	<td style="width: 25%; text-align: center; "><a id="typlink<?php echo $row['id_nota'] ?>" data-id="<?php echo $row['id_nota'] ?>" data-permission="<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal"><?php print $row['tipo_nota'] ?></a></td>
	<td style="width: 20%; text-align: center; "><a id="doclink<?php echo $row['id_nota'] ?>" data-id="<?php echo $row['id_nota'] ?>" data-permission="<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal"><?php if($row['id_tiponota'] > 10) print "--"; else print $row['cognome']." ".$row['nome'] ?></a></td>
	<td style="width: 45%; text-align: center; "><a id="comlink<?php echo $row['id_nota'] ?>" data-id="<?php echo $row['id_nota'] ?>" data-permission="<?php if ($row['docente'] == $_SESSION['__user__']->getUid()) echo 1; else echo 0 ?>" class="note_link" href="#" style="font-weight: normal"><?php print $row['descrizione'] ?></a></td>
</tr>
<?php 
	$index++;
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>
</tfoot>
</table>
</form>
<p>
</p> 
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu">
			<a href="registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro di classe</a>
		</div>
		<div class="drawer_link submenu separator">
			<a href="stats.php"><img src="../../../images/18.png" style="margin-right: 10px; position: relative; top: 5%"/>Statistiche</a>
		</div>
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
				<a href="<?php echo getFileName() ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>">
					<img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
					Classe <?php echo $cl['classe'] ?>
				</a>
			</div>
		<?php
		}
	}
	?>
</div>
<!-- tipi nota -->
<div id="div_tipinota" style="display: none; height: 210px">
    <p><a style="font-weight: normal; padding-left: 5px" href="notes.php?q=<?php echo $q ?>&order=data" class="material_link">Tutte le note</a></p>
<?php
while($t = $res_tipi->fetch_assoc()){
?>
    <p><a style="font-weight: normal; padding-left: 5px" href="notes.php?q=<?php echo $q ?>&order=data&tipo=<?= $t['id_tiponota'] ?>" class="material_link"><?php echo $t['descrizione'] ?></a></p>
<?php } ?>
</div>
<!-- tipi nota -->
<div id="nota" style="display: none">
	<iframe id="iframe" src="new_note.php" style="width: 450px; height: 250px; padding: 0; margin: 0"></iframe>
</div>
</body>
</html>
