<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Statistiche</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = 0;

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

		var dettaglio_assenze = function(f_id, q){
			$('#context_menu').hide();
			if (f_id == 0) {
				$('#iframe').attr("src", "elenco_assenze.php?alunno="+stid);
				lab_title = "Elenco assenze";
			}
			else {
				$('#iframe').attr("src", "dettaglio_rit_uscite.php?alunno="+stid+"&q="+q);
				lab_title = "Ritardi";
			}
			$('#abs_pop').dialog({
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
				title: lab_title,
				open: function(event, ui){

				}
			});
			//var w = new Window({className: "mac_os_x",  width:400, zIndex: 100, resizable: true, title: "Elenco assenze", url: "elenco_assenze.php?alunno="+stid, showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
			//w.showCenter(true);
		}

		var dialogclose = function(){
			$('#abs_pop').dialog("close");
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('context_menu').mouseleave(function(event){
				event.preventDefault();
				$('#context_menu').hide();
			});
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
	<style>
		.ui-dialog .ui-dialog-content {
			padding: 0
		}
	</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<form>
		<div style="top: -20px; margin-left: 905px; margin-bottom: -10px" class="rb_button">
			<a href="pdf_stats.php?q=<?php echo $q ?>">
				<img src="../../../images/pdf-32.png" style="padding: 4px 0 0 7px" />
			</a>
		</div>
	<table class="registro">
	<thead>
	<tr class="head_tr_no_bg">
		<td style="text-align: left; font-weight: bold; padding-left: 8px">Dati complessivi</td>
		<td colspan="2" style="text-align: center; font-weight: bold">Giorni di lezione: <?php print $totali['giorni'] ?> (<span class="attention"><?php print $totali['limite_giorni'] ?></span>)</td>
		<td colspan="2" style="text-align: center; font-weight: bold">Ore di lezione: <?php print $totali['ore']->toString(RBTime::$RBTIME_SHORT) ?> (<span class="attention"><?php print $totali['limite_ore']->toString(RBTime::$RBTIME_SHORT) ?></span>)</td>
	</tr>
	<tr class="title_tr">
		<td style="width: 40%; font-weight: bold; padding-left: 8px">Alunno</td>
		<td style="width: 15%; text-align: center; font-weight: bold">Assenze</td>
		<td style="width: 15%; text-align: center; font-weight: bold">% assenze</td>
		<td style="width: 15%; text-align: center; font-weight: bold">Ore assenza</td>
		<td style="width: 15%; text-align: center; font-weight: bold">% ore assenza</td>
	</tr>
	</thead>
	<tbody>
	<?php
	$idx = 0;
	foreach ($presence as $k => $row){
		$perc_day = round((($row['absences'] / $totali['giorni']) * 100), 2);
	    $absences = new RBTime(0, 0, 0);
	    $absences->setTime($totali['ore']->getTime() - $row['presence']->getTime());
	    $perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);
	    if($perc_day == 0){
			$perc_day = "--";
		}
		else{
			$perc_day .= "%";
		}
		if($perc_hour == 0){
			$perc_hour = "--";
		}
		else{
			$perc_hour .= "%";
		}
		$background = "";
	?>
	<tr>
		<td style="width: 35%; padding-left: 8px"><a href="#" data-id="<?php print $k ?>" class="st_link" style="font-weight: normal; color: inherit"><?php print stripslashes($row['name']) ?></a></td>
		<td style="width: 15%; text-align: center"><?php print $row['absences'] ?></td>
		<td style="width: 15%; text-align: center;" <?php if($perc_day > 24.99) print("class='attention _bold'") ?>><?php print $perc_day ?></td>
		<td style="width: 15%; text-align: center"><?php print ($absences->getTime() > 0) ? $absences->toString(RBTime::$RBTIME_SHORT) : "--" ?></td>
		<td style="width: 15%; text-align: center;" <?php if($perc_hour > 24.99) print("class='attention _bold'") ?>><?php print $perc_hour ?></td>
	</tr>
	<?php
		$idx++;
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr  class="nav_tr">
			<td colspan="5" style="text-align: center; height: 40px">
				<a href="stats.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />1 Quadrimestre
				</a>
				<a href="stats.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />2 Quadrimestre
				</a>
				<a href="stats.php?q=0" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />Totale
				</a>
			</td>
		</tr>
	</tfoot>
	</table>
	</form>
<p></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu">
			<a href="registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro di classe</a>
		</div>
		<div class="drawer_link submenu separator">
			<a href="notes.php"><img src="../../../images/26.png" style="margin-right: 10px; position: relative; top: 5%"/>Note</a>
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
<div id="abs_pop" style="display: none">
	<iframe id="iframe" src="new_note.php" style="width: 100%; height: 450px; margin: 0 auto; padding: 0; border: 0"></iframe>
</div>
<!-- menu contestuale -->
<div id="context_menu" style="position: absolute; width: 210px; height: 50px; display: none; ">
	<a style="font-weight: normal" href="#" onclick="dettaglio_assenze(0, 0)">Elenco assenze</a><br />
	<a style="font-weight: normal" href="#" onclick="dettaglio_assenze(1, <?php print $q ?>)">Elenco ritardi e uscite anticipate</a><br />
</div>
<!-- fine menu contestuale -->
</body>
</html>
