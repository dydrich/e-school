<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: scrutini</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
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
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td style="text-align: center"><span id="ingresso" style=""><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php print ($num_subject * 2) ?>" style="text-align: center">Quadro riassuntivo</td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: <?php print $first_column ?>%; font-weight: bold; padding-left: 8px">Alunno</td>
	<?php
	$num_alunni = $res_alunni->num_rows;
	$tot_materie = array();
	foreach ($_SESSION['__subjects__'] as $materia) {
		if(!isset($tot_materie[$materia['id']])){
			$tot_materie[$materia['id']] = array();
			$tot_materie[$materia['id']]['voto'] = 0;
			$tot_materie[$materia['id']]['abs'] = 0;
			$tot_materie[$materia['id']]['num_al'] = $num_alunni;
		}
	?>
	<td colspan="2" style="width: <?php print $other_column * 2 ?>%; text-align: center; font-weight: bold"><a href="scrutini.php?q=<?php echo $q ?>&subject=<?php echo $materia['id'] ?>" style="color: black"><?php print $materia['mat'] ?></a></td>
	<?php 
	}
	?>
</tr>
<tr class="title_tr">
	<?php 
	for($i = 0; $i < count($_SESSION['__subjects__']); $i++){
	?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weght: bold">Voto</td>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weght: bold">Assenze</td>
	<?php 
	}
	?>
</tr>
</thead>
<tbody>
<?php
$idx = 0;
while($al = $res_alunni->fetch_assoc()){
?>
<tr>
	<td style="width: <?php print $first_column ?>%;  padding-left: 8px; font-weight: bold"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?><span style="font-weight: normal"><?php print $al['cognome']." ".$al['nome']?></span></td>
<?php 
	reset($_SESSION['__subjects__']);
	foreach ($_SESSION['__subjects__'] as $materia) {
		$sel_voti = "SELECT voto, assenze FROM rb_scrutini WHERE alunno = ".$al['id_alunno']." AND materia = ".$materia['id']." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = $q";
		try{
			$res_voti = $db->executeQuery($sel_voti);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		$dt = $res_voti->fetch_assoc();
		if($dt['voto'] > 0){
			$tot_materie[$materia['id']]['voto'] += $dt['voto'];
			$tot_materie[$materia['id']]['abs'] += $dt['assenze'];
		}
		else {
			--$tot_materie[$materia['id']]['num_al'];
		}
?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: bold"><span class="<?php if($dt['voto'] < 6 && $dt['voto'] > 0) print("attention") ?>"><?php echo $dt['voto'] ?></span></td>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: normal"><?php if ($ordine_scuola == 1) echo $dt['assenze']; else echo "--" ?></td>
<?php 
	}
?>	
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr class="riepilogo">
<td style="width: <?php print $first_column ?>%; padding-left: 8px; font-weight: bold">Media classe</td>
	<?php
	
	reset($_SESSION['__subjects__']);
	foreach ($_SESSION['__subjects__'] as $materia) {
		$avg = "--";
		$abs_avg = "--";
		if($tot_materie[$materia['id']]['num_al'] > 0){
			$avg = $tot_materie[$materia['id']]['voto'] / $tot_materie[$materia['id']]['num_al'];
			$abs_avg = round(($tot_materie[$materia['id']]['abs'] / $tot_materie[$materia['id']]['num_al']), 2);
		}
	?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: bold"><span class="<?php if($avg < 6) print("attention") ?>"><?php echo round($avg, 2) ?></span></td>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: normal"><?php if ($ordine_scuola == 1) echo $abs_avg; else echo "--" ?></td>
	<?php 
	}
	?>
</tr>
<tr>
	<td colspan="<?php print (($num_subject*2) + 1) ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php print (($num_subject*2) + 1) ?>" style="text-align: center; height: 40px">
		<a href="riepilogo_scrutini.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />1 Quadrimestre
		</a>
		<a href="riepilogo_scrutini.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />2 Quadrimestre
		</a>
	</td>
</tr>
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
		<?php if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == 'rbachis') { ?>
		<div class="drawer_link submenu separator">
			<a href="scrutini_classe.php?q=<?php echo $q ?>"><img src="../../../images/74.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini classe</a>
		</div>
		<?php
		}
		?>
		<?php if ($q == 2): ?>
		<div class="drawer_link separator">
			<a href="confronta_scrutini.php"><img src="../../../images/46.png" style="margin-right: 10px; position: relative; top: 5%"/>Confronta scrutini</a>
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
</body>
</html>
