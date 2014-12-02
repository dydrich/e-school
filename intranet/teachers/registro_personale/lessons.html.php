<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: lezioni</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var change_subject = function(id){
			document.location.href="lessons.php?subject="+id+"&q=<?php print $q ?>";
		};

		var show_absences = function(id){
			$('#iframe').attr("src", "show_hour_absences.php?idh="+id);
			$('#if_pop').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 500
				},
				hide: {
					effect: "slide",
					duration: 400
				},
				modal: true,
				width: 450,
				title: 'Assenze ora',
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

		var dialogclose = function(){
			$('#if_pop').dialog("close");
		};

		$(function() {
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
table.registro td {
	border: 0px
}
.ui-dialog .ui-dialog-content {
	padding: 0
}
</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div class="mdtabs">
		<?php
		if(count($_SESSION['__subjects__']) > 0){
			$k = 0;
			$materie = array();
			foreach ($_SESSION['__subjects__'] as $mt) {
				$selected = false;
				if(isset($_REQUEST['subj'])){
					if($_REQUEST['subj'] == $mt['id']){
						$idm = $mt['id'];
						$_mat = $mt['mat'];
						$selected = true;
					}
				}
				else if(isset($_SESSION['__materia__'])){
					if($_SESSION['__materia__'] == $mt['id']){
						$idm = $mt['id'];
						$_mat = $mt['mat'];
						$selected = true;
					}
					else if($k == 0){
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
				}
				else if($k == 0){
					//print "k==0";
					$idm = $mt['id'];
					$_mat = $mt['mat'];
					$selected = true;
				}
				$k++;
				?>
				<div class="mdtab<?php if ($selected) echo " mdselected_tab" ?>">
					<a href="#" onclick='change_subject(<?php echo $mt['id'] ?>)'><span><?php echo truncateString($mt['mat'], 25) ?></span></a>
				</div>
			<?php
			}
			$_SESSION['__materia__'] = $idm;
		}
		?>
	</div>
	<div style="width: 75%; float: left; border-right: 1px solid #CCCCCC; min-height: 250px; margin-bottom: 20px">
<table class="registro" style="width: 95%; margin: 0 auto 0 auto">
	<thead>
	</thead>
	<tbody>
	<?php
	$day = "";
	$mese = "";
	while($les = $res_lessons->fetch_assoc()){
		setlocale(LC_TIME, "it_IT.utf8");
		list($y, $m, $d) = explode("-", $les['data']);
		if($group){
			if($mese != $m){
				$str_month = ucfirst(strftime("%B", strtotime($les['data'])));
				print("<tr class='high_contrast_row'><td colspan='3' style='height: 20px; vertical-align: middle; text-transform: uppercase; font-size: 1.1em; text-align: center; '>$str_month</td></tr>");
			}
		}
		$giorno_str = $group ? ucfirst(strftime("%A %d", strtotime($les['data']))) : ucfirst(strftime("%A %d %B", strtotime($les['data'])));
		$print_day = ($day != $les['data']) ? true : false;
	?>
	<tr class="bottom_decoration">
		<td colspan="2" style="width: 25%; text-align: left; padding-left: 20px; font-weight: normal; "><?php if($print_day) print $giorno_str ?></td>
		<td style="width: 75%; text-align: left; font-weight: normal;">
			<span id="lesson_<?php echo $les['id'] ?>" onclick="show_absences(<?php echo $les['id'] ?>)"><?php print stripslashes($les['argomento']) ?></span>
		</td>
	</tr>
	<?php
		$day = $les['data'];
		$mese = $m;
	}
	?>
	</tbody>
</table>
	</div>
	<div style="float: right; width: 23%">
		<ul>
		<?php
		foreach ($months as $k => $itm) {
		?>
		<li><a href="lessons.php?month=<?php echo $k ?>"><?php echo $itm['italian'] ?></a></li>
		<?php
		}
		?>
			<li><a href="lessons.php">Tutte le lezioni</a></li>
		</ul>
	</div>
	<div class="navigate" style="clear: both">
		<a href="lessons.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/24.png" /><span>1 Quadrimestre</span>
		</a>
		<a href="lessons.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/24.png" /><span>2 Quadrimestre</span>
		</a>
		<a href="lessons.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/24.png" /><span>Totale</span>
		</a>
	</div>
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
<div id="if_pop" style="display: none">
	<iframe id="iframe" src="show_hour_absences.php" style="width: 100%; height: 380px; margin: 0 auto; padding: 0; border: 0"></iframe>
</div>
</body> 
</html>
