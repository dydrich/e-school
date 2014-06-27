<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Lezioni docente</title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script>
		var subj = <?php echo $start_subj ?>;
		$(function() {
			$(".tab").click(function(){
				data = this.id.split("_");
				$(".table_tab").hide();
				$(".tab").removeClass("selected");
				$("#tab_"+data[1]).show(400);
				$(this).addClass("selected");
				subj = data[1];
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
		<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
			<?php echo $page_label ?>
		</div>
		<div id="tabs" style="">
			<?php
			foreach ($lessons as $k => $cl){
				?>
				<div id="stab_<?php echo $k ?>" class="tab <?php if ($start_subj == $k) echo "selected" ?>"><?php echo strtoupper(substr($cl['materia'], 0, 3)) ?></div>
			<?php
			}
			?>
			<div id="lessons" class="fright" style="margin-top: 5px"><a href="#" id="load_reg" class="standard_link" style="margin-right: 10px">Voti docente</a></div>
		</div>
		<?php
		reset($lessons);
		setlocale(LC_TIME, "it_IT");
		foreach ($lessons as $k => $lesson){
			?>
			<table id="tab_<?php echo $k ?>" class="wd_95 _elem_center table_tab" style="<?php if ($k != $start_subj) echo "display: none" ?>">
				<thead>
				<tr style="border-bottom: 1px solid #CCC">
					<td class="_center _bold" colspan="2">Materia: <?php echo $lesson['materia'] ?></td>
				<tr>
				<tr style="border-bottom: 2px solid #CCC">
					<td class="_bold" style="width: 25%; padding-left: 8px">DATA</td>
					<td class="_bold" style="width: 75%">ARGOMENTO</td>
				</tr>
				</thead>
				<tbody>
				<?php
				$day = null;
				$mese = null;
				setlocale(LC_TIME, "it_IT.UTF-8");
				foreach ($lesson['lezioni'] as $j => $lezione){
					list($y, $m, $d) = explode("-", $lezione['data']);
					if($mese != $m){
						$str_month = ucfirst(strftime("%B", strtotime($lezione['data'])));
						print("<tr><td colspan='2' style='height: 20px; vertical-align: middle; font-weight: normal; text-transform: uppercase; font-size: 13m; text-align: center; border-bottom: 1px solid #CCCCCC; background-color: rgba(211, 222, 199, 0.3); '>$str_month</td></tr>");
					}

					$giorno_str = ucfirst(strftime("%A %d", strtotime($lezione['data'])));
					$print_day = ($day != $lezione['data']) ? true : false;
				?>
					<tr style="border-bottom: 1px solid #CCC">
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
</body>
</html>