<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Scrutini</title>
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
		<?php echo $change_subject->getJavascript() ?>
		function change_subject(id){
			document.location.href="confronta_scrutini.php?subject="+id;
		}

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
	<!-- div nascosto, per la scelta della materia -->
	<?php $change_subject->toHTML() ?>
	<form method="post">
		<table class="registro">
			<thead>
			<tr class="head_tr">
				<td colspan="2" style="text-align: center; font-weight: bold"><?php print $_SESSION['__current_year__']->to_string() ?> - Confronto scrutini</td>
				<td colspan="2" style="text-align: right; "></td>
			</tr>
			<tr class="head_tr_no_bg">
				<td colspan="1" style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
				<td colspan="3" style="text-align: center; ">Materia: <span id="uscita" style="font-weight: bold; "><?php $change_subject->printLink() ?></span></td>
			</tr>
			<tr class="title_tr">
				<td style="width: 48%; font-weight: bold; padding-left: 8px">Alunno</td>
				<td style="width: 17%; text-align: center; font-weight: bold">Voto 1Q</td>
				<td style="width: 17%; text-align: center; font-weight: bold">Voto 2Q</td>
				<td style="width: 17%; text-align: center; font-weight: bold">Differenza</td>
			</tr>
			</thead>
			<tbody>
			<?php
			$idx = 0;
			$differenza_totale = 0;
			foreach ($dati as $row){
				$background = "";

				if($idx%2)
					$background = "background-color: #e8eaec";
				?>
				<tr>
					<td style="width: 48%; padding-left: 8px; font-weight: bold;"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
						<span style="font-weight: normal"><?php print $row['lname']." ".$row['fname']?></span>
					</td>
					<td style="width: 17%; text-align: center; font-weight: normal;">
						<?php
							if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
						?>
								<span><?php echo $voti_religione[RBUtilities::convertReligionGrade($row['voto1q'])] ?></span>
						<?php
							}
							else {
						?>
								<span><?php echo $row['voto1q'] ?></span>
						<?php
							}
						?>

					</td>
					<td style="width: 17%; text-align: center; font-weight: normal;">
						<?php
						if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
							?>
							<span><?php echo $voti_religione[RBUtilities::convertReligionGrade($row['voto2q'])] ?></span>
						<?php
						}
						else {
							?>
							<span><?php echo $row['voto2q'] ?></span>
						<?php
						}
						?>

					</td>
				<?php
				$diff = $row['voto2q'] - $row['voto1q'];
				$differenza_totale += $diff;
				?>
					<td style="width: 17%; text-align: center; font-weight: normal" <?php if ($diff < 0): ?>class="_bold attention" <?php endif; ?> >
						<span <?php if ($diff > 0): ?>class="_bold"<?php endif; ?>><?php if ($diff > 0) echo "+" ?><?php echo $diff ?></span>
					</td>
				</tr>
				<?php
				$idx++;
			}
			?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="4" style="height: 15px"></td>
			</tr>
			<tr style="font-weight: bold; height: 30px; background-color: #e8eaec">
				<td style="padding-left: 10px">Media classe</td>
				<td style="text-align: center"><?php echo $avg1 ?></td>
				<td style="text-align: center"><?php echo $avg2 ?></td>
				<td style="text-align: center">
					<span <?php if ($differenza_totale != 0): ?>class="_bold <?php if ($differenza_totale < 0) echo 'attention' ?>"<?php endif; ?>><?php if ($differenza_totale > 0) echo "+" ?>
						<?php echo $differenza_totale ?>
					</span>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="height: 15px"></td>
			</tr>
			</tfoot>
		</table>
	</form>
</div>
<?php include "../footer.php" ?>
</body>
</html>
