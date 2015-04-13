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
				if ($mat['mat'] == "Materia alternativa") {
					$mat['mat'] = "Mat. alt.";
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
	<form method="post">
		<table class="registro">
			<thead>
			<tr class="head_tr_no_bg">
				<td colspan="4" style="text-align: center; border: 0">
					<span id="ingresso" style="font-weight: normal; "><?php print $_SESSION['__classe__']->to_string() ?><?php echo $label_subject ?></span>
				</td>
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
			<tr style="font-weight: bold; height: 30px">
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu">
			<a href="scrutini.php?q=2"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
		</div>
		<?php if(count($_SESSION['__subjects__']) > 1){ ?>
			<div class="drawer_link submenu">
				<a href="riepilogo_scrutini.php?q=2"><img src="../../../images/65.png" style="margin-right: 10px; position: relative; top: 5%"/>Riepilogo scrutini</a>
			</div>
		<?php
		}
		if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == 'rbachis') { ?>
			<div class="drawer_link submenu separator">
				<a href="scrutini_classe.php?q=2"><img src="../../../images/74.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini classe</a>
			</div>
		<?php
		}
		?>
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
			<a href="index.php?q=2&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1"><img src="../../../images/31.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro per obiettivi</a>
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
