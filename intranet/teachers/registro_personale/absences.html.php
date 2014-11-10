<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: assenze</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var change_subject = function(id){
			document.location.href="absences.php?subject="+id;
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
<form action="student.php" method="post">
<?php 
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td colspan="7" style="text-align: center; border-top: 0"><span id="ingresso" style=""><?php print $_SESSION['__classe__']->to_string() ?><?php echo $label_subject ?></span></td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: 40%; font-weight: bold; padding-left: 8px">Alunno</td>
	<td colspan="2" style="width: 20%; text-align: center; font-weight: bold">Totale: <?php print $hours_count ?></td>
	<td colspan="2" style="width: 20%; text-align: center; font-weight: bold">1 quadrimestre: <?php print $q1_hours_count ?></td>
	<td colspan="2" style="width: 20%; text-align: center; font-weight: bold">2 quadrimestre: <?php print $q2_hours_count ?></td>
</tr>
<tr class="title_tr">
	<td style="width: 10%; text-align: center; font-weight: bold">Ore assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">% assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">Ore assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">% assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">Ore assenza</td>
	<td style="width: 10%; text-align: center; font-weight: bold">% assenza</td>
</tr>
</thead>
<tbody>
<?php 
$idx = 0;
foreach($students as $student){
	$tot_per = $tot_q1_per = 0;
	$background = "";
	if($idx%2)
		$background = "background-color: #e8eaec";

	/*
	 * 1. trasformo i totali ore in minuti
	 * 2. calcolo le percentuali di assenza
	 * trasformo i minuti di assenza nel formato ore:minuti
	 */
	$tot_hours = $hours_count * 60;
	$tot_q1 = $q1_hours_count * 60;
	$tot_q2 = $q2_hours_count * 60;
	
	$tot_absence = minutes2hours($tot_hours, "/");
	$q1_absence = minutes2hours($tot_q1, "/");
	$q2_absence = minutes2hours($tot_q2, "/"); 
	
	$st_absence = minutes2hours($student['absence_time'], "/");
	$st_1q_absence = minutes2hours($student['absence_time_1q'], "/");
	if(date("Y-m-d") > $fine_q){
		$st_2q_absence = minutes2hours($student['absence_time_2q'], "/");
		if ($tot_q2 > 0){
			$tot_q2_per = round((($student['absence_time_2q'] / $tot_q2) * 100), 2)."%";
		}
		else {
			$tot_q2_per = "0%";
		}
	}
	else{
		$st_2q_absence = "--";
		$tot_q2_per = "--";
	}
	
	if($tot_hours > 0){
		$tot_per = round((($student['absence_time'] / $tot_hours) * 100), 2);
		if($tot_per > 0){
			$tot_per .= "%";
		}
		else {
			$tot_per = "/";
		}
	}
	if($tot_q1 > 0){	
		$tot_q1_per = round((($student['absence_time_1q'] / $tot_q1) * 100), 2);
		if($tot_q1_per > 0){
			$tot_q1_per .= "%";
		}
		else {
			$tot_q1_per = "/";
		}
	}	
	
?>
<tr>
	<td style="width: 40%; padding-left: 8px; font-weight: bold; "><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?><span style="font-weight: normal; " ><?php print $student['cognome']." ".$student['nome']?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_per > 25) print("attention _bold") ?>"><?php print $st_absence ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_per > 25) print("attention _bold") ?>"><?php print $tot_per ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q1_per > 25) print("attention _bold") ?>"><?php print $st_1q_absence ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q1_per > 25) print("attention _bold") ?>"><?php print $tot_q1_per ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q2_per > 25) print("attention _bold") ?>"><?php print $st_2q_absence ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: normal;"><span class="<?php if($tot_q2_per > 25) print("attention _bold") ?>"><?php print $tot_q2_per ?></span></td>	
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="8" style="text-align: right; font-weight: bold; margin-right: 30px">&nbsp;
		<input type="hidden" name="id_materia" value="<?php if (isset($idm)) echo $idm ?>" />
		<input type="hidden" name="materia" value="<?php if (isset($_mat)) echo $_mat ?>" />
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
