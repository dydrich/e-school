<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
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
		table.registro td {
			border-width: 0 0 1px 0;
		}
	</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div class="mdtabs">
		<div class="mdtab<?php if (isset($_REQUEST['group']) && $_REQUEST['group'] == 1) echo " mdselected_tab" ?>">
			<a href="grades_list.php?q=<?php echo $q ?>&order=<?php echo $order ?>&stid=<?php echo $student ?>&field_order=data_voto&group=1"><span>Mensile</span></a>
		</div>
		<div class="mdtab<?php if (isset($_REQUEST['group']) && $_REQUEST['group'] == 2) echo " mdselected_tab" ?>">
			<a href="grades_list.php?q=<?php echo $q ?>&order=<?php echo $order ?>&stid=<?php echo $student ?>&field_order=rb_materie.materia,data_voto&group=2"><span>Materia</span></a>
		</div>
		<div class="mdtab<?php if ((isset($_REQUEST['group']) && $_REQUEST['group'] == 0) || (!isset($_REQUEST['group']))) echo " mdselected_tab" ?>">
			<a href="grades_list.php?q=<?php echo $q ?>&order=<?php echo $order ?>&stid=<?php echo $student ?>&field_order=data_voto&group=0"><span>Normale</span></a>
		</div>
	</div>
<table class="registro" style="width: 95%; margin: auto">
	<thead>
	</thead>
	<tbody>
	<?php
	if (!isset($_REQUEST['group']) || $_REQUEST['group'] == 0) {
	?>
		<tr class='high_contrast_row'>
			<td colspan='5' style='text-transform: uppercase; font-size: 1.1em; text-align: center; ' class=''>Media voto
				<span id='avg_tot' style='font-size: 1.0em'></span>
			</td>
		</tr>
	<?php
	}
	$idx = 0;
	$day = "";
	$mese = "";
	$subject = "";
	$count_per_month = 0;
	$count_per_subject = 0;
	$sum_month = $sum_subject = 0;
	$first_month = "";
	$sum = 0;
	while($voto = $res_voti->fetch_assoc()){
		setlocale(LC_TIME, "it_IT.utf8");
		list($y, $m, $d) = explode("-", $voto['data_voto']);
		if($idx == 0) $first_month = $m;
		if($group){
			if($_REQUEST['group'] == 1){
				if($mese != $m){
					if($mese != "")
						$avg = round(($sum_month / $count_per_month), 2);
					$str_month = ucfirst(strftime("%B", strtotime($voto['data_voto'])));
					print("<tr class='high_contrast_row'><td colspan='5' style='text-transform: uppercase; font-size: 1.1em; text-align: center; '>$str_month<span id='avg_per_month_$m' style='font-weight: normal; font-size: 1.0em'></span></td></tr>");
					if($mese != "")
						print("<script>$('#avg_per_month_$mese').text(' ($avg)');</script>");
					$count_per_month = $sum_month = 0;
				}
			}
			else{
				if($subject != $voto['mat']){
					if($subject != "")
						$avg = round(($sum_subject / $count_per_subject), 2);
					print("<tr class='high_contrast_row'><td colspan='5' style='text-transform: uppercase; font-size: 1.1em; text-align: center; ' class=''>".$voto['mat']."<span id='avg_per_".$voto['mat']."' style='font-weight: normal; font-size: 1.0em'></span></td></tr>");
					if($subject != "")
						print("<script>$('#avg_per_$subject').text(' ($avg)');</script>");
					$count_per_subject = $sum_subject = 0;
				}
			}
		}
		$sum_month += $voto['voto'];
		$sum_subject += $voto['voto'];
		$sum += $voto['voto'];
		$count_per_month++;
		$count_per_subject++;
		$giorno_str = (isset($_REQUEST['group']) && $_REQUEST['group'] == 1) ? ucfirst(strftime("%A %d", strtotime($voto['data_voto']))) : ucfirst(strftime("%A %d %B", strtotime($voto['data_voto'])));
		$print_day = ($day != $voto['data_voto']) ? true : false;
		if($voto['voto'] < 6)
			$color = "attention";
		else
			$color = "";
	?>
	<tr class="bottom_decoration <?php echo $color ?>">
		<td colspan="2" style="width: 25%; text-align: left; padding-left: 20px; font-weight: normal"><?php if($print_day) print $giorno_str ?></td>
		<td style="width: 15%; text-align: center; font-weight: normal"><?php echo $voto['voto'] ?></td>
		<td style="width: 15%; text-align: center; font-weight: normal"><?php echo $voto['mat'] ?></td>
		<td style="width: 55%; text-align: center; font-weight: normal"><?php print $voto['descrizione'] ?></td>
	</tr>
	<?php
		$day = $voto['data_voto'];
		$mese = $m;
		$subject = $voto['mat'];
		$idx++;
	}
	$avg = round(($sum_month / $count_per_month), 2);
	print("<script>$('#avg_per_month_$mese').text(' ($avg)');</script>");
	$avg = round(($sum_subject / $count_per_subject), 2);
	print("<script>$('#avg_per_$subject').text(' ($avg)');</script>");
	$avg = round(($sum / $res_voti->num_rows), 2);
	print("<script>$('#avg_tot').text(' ($avg)');</script>");
	?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
	<div class="navigate">
		<a href="grades_list.php?q=1&order=<?php echo $order_to ?>&stid=<?php echo $student ?>&field_order=<?php echo $field_order ?>&group=<?php echo ($group) ? 1 : 0 ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; position: relative; top: 4px; color: #000000">
			<img style="margin-right: 5px; position: relative; top: 3px" src="../../../images/24.png" />1 Quadrimestre
		</a>
		<a href="grades_list.php?q=2&order=<?php echo $order_to ?>&stid=<?php echo $student ?>&field_order=<?php echo $field_order ?>&group=<?php echo ($group) ? 1 : 0 ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; position: relative; top: 4px; margin-left: 8px; color: #000000">
			<img style="margin-right: 5px; position: relative; top: 3px" src="../../../images/24.png" />2 Quadrimestre
		</a>
		<a href="grades_list.php?q=0&order=<?php echo $order_to ?>&stid=<?php echo $student ?>&field_order=<?php echo $field_order ?>&group=<?php echo ($group) ? 1 : 0 ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; position: relative; top: 4px; color: #000000">
			<img style="margin-right: 5px; position: relative; top: 3px" src="../../../images/24.png" />Totale
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
