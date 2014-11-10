<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Lezioni docente</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		var subj = <?php echo $start_subj ?>;
		$(function() {
			load_jalert();
			setOverlayEvent();
			$(".mdtab").click(function(){
				data = $(this).attr("data-id");
				$(".table_tab").hide();
				$(".mdtab").removeClass("mdselected_tab");
				$("#tab_"+data).show(400);
				$(this).addClass("mdselected_tab");
				subj = data;
			});
			$("#load_reg").click(function(event){
				event.preventDefault();
				load_grades(<?php echo $teacher ?>);
			});
		});

		var load_grades = function(doc){
			document.location.href = "registro_docente.php?doc="+doc+"&cls=<?php echo $cls->get_ID() ?>";
		};
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
			foreach ($lessons as $k => $cl) {
					?>
			<div class="mdtab<?php if ($start_subj == $k) echo " mdselected_tab" ?>" data-id="<?php echo $k ?>">
				<a href="#"><span><?php echo strtoupper(substr($cl['materia'], 0, 3)) ?></a></span>
			</div>
				<?php
			}
			?>
	</div>
		<?php
		reset($lessons);
		setlocale(LC_TIME, "it_IT.utf8");
		foreach ($lessons as $k => $lesson){
			?>
			<table id="tab_<?php echo $k ?>" class="wd_95 _elem_center table_tab" style="<?php if ($k != $start_subj) echo "display: none" ?>">
				<thead>
				<tr >
					<td colspan="2">
						<div class="rowcard _bold" style="width: 95%">Lezioni di <?php echo $lesson['materia'] ?></div>
					</td>
				<tr>
				<tr style="">
					<td class="_bold" style="width: 25%; padding-left: 8px"></td>
					<td class="_bold" style="width: 75%"> </td>
				</tr>
				</thead>
				<tbody>
				<?php
				$day = null;
				$mese = null;
				foreach ($lesson['lezioni'] as $j => $lezione){
					list($y, $m, $d) = explode("-", $lezione['data']);
					if($mese != $m){
						$str_month = ucfirst(strftime("%B", strtotime($lezione['data'])));
						print("<tr><td colspan='2' style='height: 20px; vertical-align: middle; font-weight: normal; text-transform: uppercase; font-size: 1.1em; text-align: center; '>$str_month</td></tr>");
					}

					$giorno_str = ucfirst(strftime("%A %d", strtotime($lezione['data'])));
					$print_day = ($day != $lezione['data']) ? true : false;
				?>
					<tr class="bottom_decoration">
						<td style="width: 25%; padding-left: 8px"><?php if($print_day) print $giorno_str ?></td>
						<td style="width: 75%"><?php echo $lezione['argomento'] ?></td>
					</tr>
				<?php
					$day = $lezione['data'];
					$mese = $m;
				}
				?>
				</tbody>
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
		<div class="drawer_link separator"><a href="#" id="load_reg"><img src="../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Voti docente</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<?php if ($_SESSION['__role__'] == "Dirigente scolastico"): ?>
			<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
		<?php endif; ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
