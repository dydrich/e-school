<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
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
		var stid = 0;

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

		var change_subject = function(id){
			document.location.href="riepilogo_verifiche.php?subject="+id+"&q=<?php echo $q ?>";
		};

		var show_menu = function(e, _stid, offset){
			if ($('#context_menu').is(":visible")) {
				$('#context_menu').slideUp(300);
				return;
			}
			$('#context_menu').css({'top': offset.top+"px"});
			$('#context_menu').css({'left': offset.left+"px"});
			$('#context_menu').slideDown(500);
			stid = _stid;
			return false;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#context_menu').mouseleave(function(event){
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
				$('#other_drawer').hide();
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				show_classlist(event, off);
			}).css({
				cursor: "pointer"
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
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
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
	<div id="main" style="clear: both; ">
	<?php
	$label_subject = "";
	if (count($materie) > 1) {
		?>
		<div class="mdtabs">
			<?php
			foreach ($materie as $mat) {
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

	setlocale(LC_TIME, "it_IT.utf8");
	?>
		<table class="registro">
			<thead>
				<tr class="head_tr_no_bg">
					<td colspan="<?php echo $total_cols ?>" style="text-align: center; border-top: 0"><span id="ingresso" style=""><?php print $_SESSION['__classe__']->to_string() ?><?php echo $label_subject ?></span></td>
				</tr>
				<tr class="title_tr">
					<td style="width: 20%; padding-left: 10px" class="_bold">
						Alunno
					</td>
					<?php
					while ($row = $res_tests->fetch_assoc()) {
						$giorno_str = strftime("%a %d %b", strtotime($row['data_verifica']));
					?>
					<td style="width: <?php echo $cols_length ?>%" class="_center _bold">
						<?php echo $giorno_str ?>
					</td>
					<?php
					}
					?>
				</tr>
				</thead>
			<tbody>
			<?php
			$idx = 0;
			reset($alunni);
			foreach ($alunni as $id_al => $al){

			?>
				<tr id="tr<?php echo $id_al ?>">
					<td style="width: 20%; padding-left: 8px; font-weight:bold; "><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
						<a href="#" data-id="<?php echo $al['data']['id_alunno'] ?>" class="st_link" style="font-weight: normal; color: inherit; padding-left: 8px"><?php print $al['data']['cognome']." ".$al['data']['nome']?></a>
					</td>
					<?php
					foreach ($al['tests'] as $k => $test){
						if (!isset($al['tests'][$k])) {
							$al['tests'][$k] = 0;
						}
						if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
							if($test < 5.5){
								$test = 4;
							}
							else if ($test > 6.49 && $test < 8){
								$test = 8;
							}
							$_voto = $voti_religione[$test];
						}
						else{
							$_voto = $test;
						}
						?>
						<td id="avgtipo<?php echo $k ?>_<?php echo $id_al; ?>" style="width: <?php echo $cols_length ?>%; text-align: center; font-weight: bold;" class="<?php if($test > 0 && $test < 6) echo "attention" ?>"><?php if($test < 1) echo "--"; else echo $_voto ?></td>
					<?php } ?>
				</tr>
				<?php
				$idx++;
			}
			?>
			</tbody>
			<tfoot>

			<tr>
				<td colspan="<?php echo $total_cols ?>" style="text-align: right; font-weight: bold; margin-right: 30px">&nbsp;
				</td>
			</tr>
			<tr class="nav_tr">
				<td colspan="<?php echo $total_cols ?>" style="text-align: center; height: 40px">
					<input type="hidden" name="id_materia" value="<?php if (isset($idm)) echo $idm ?>" />
					<input type="hidden" name="materia" value="<?php if (isset($_mat)) echo $_mat ?>" />
					<a href="riepilogo_verifiche.php?q=1&subject=<?php echo $_SESSION['__materia__'] ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
						<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />1 Quadrimestre
					</a>
					<a href="riepilogo_verifiche.php?q=2&subject=<?php echo $_SESSION['__materia__'] ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
						<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />2 Quadrimestre
					</a>
					<a href="riepilogo_verifiche.php?q=0&subject=<?php echo $_SESSION['__materia__'] ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
						<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />Totale
					</a>
					<!-- <a href="index.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=0">Totale</a> -->
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
		<div class="drawer_link">
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
		<a href="riepilogo_verifiche.php"><img src="../../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Riepilogo verifiche</a>
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
</body>
</html>
