<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di sostegno</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		var _class = <?php echo $std ?>;
		$(function() {
			load_jalert();
			setOverlayEvent();
			$(".mdtabs").click(function(){
				data = $(this).attr("data-id");
				$(".table_tab").hide();
				$(".mdtab").removeClass("mdselected_tab");
				$("#tab_"+data).show(400);
				$(this).addClass("mdselected_tab");
				_class = data;
			});
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $_SESSION['__administration_group__']."/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="mdtabs">
			<?php
			foreach ($alunni as $alunno){
				?>
				<div class="mdtab<?php if ($std == $alunno['data']['id_alunno']) echo " mdselected_tab" ?>" data-id="<?php echo $alunno['data']['id_alunno'] ?>">
					<a href="#"><span><?php echo $alunno['data']['cognome']." ".substr($alunno['data']['nome'], 0, 1).". (".$alunno['data']['anno_corso'].$alunno['data']['sezione'].")" ?></a></span>
				</div>
			<?php
			}
			?>

		</div>
		<?php
		reset($alunni);
		foreach ($alunni as $k => $alunno){
			?>
			<table id="tab_<?php echo $k ?>" class="wd_95 _elem_center table_tab" style="<?php if ($alunno['data']['id_alunno'] != $std) echo "display: none" ?>">
				<?php
				$day = null;
				$mese = null;
				setlocale(LC_TIME, "it_IT.UTF-8");
				foreach ($alunno['attivita'] as $idx => $activity){
					list($y, $m, $d) = explode("-", $activity['data']);
					if($mese != $m){
						$str_month = ucfirst(strftime("%B", strtotime($activity['data'])));
						print("<tr style='height: 40px; vertical-align: bottom'><td colspan='2'><div class='rowcard _bold' style='width: 95%'>Lezioni di $str_month</div></td></tr>");
					}

					$giorno_str = ucfirst(strftime("%A %d", strtotime($activity['data'])));
					$print_day = ($day != $activity['data']) ? true : false;
				?>
				<tr style="border-bottom: 1px solid rgba(30, 67, 137, .5);">
					<td style="width: 25%; padding-left: 8px"><?php if($print_day) print $giorno_str ?></td>
					<td style="width: 75%"><?php echo $activity['attivita'] ?></td>
				</tr>
				<?php
					$day = $activity['data'];
					$mese = $m;
				}
				?>
			</table>
		<?php
		}
		?>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link separator"><a href="#" id="load_lss"><img src="../../images/62.png" style="margin-right: 10px; position: relative; top: 5%" />Lezioni docente</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
