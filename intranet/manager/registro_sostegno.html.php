<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di sostegno</title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script>
		var _class = <?php echo $std ?>;
		$(function() {
			$(".large_tab").click(function(){
				data = this.id.split("_");
				$(".table_tab").hide();
				$(".large_tab").removeClass("selected");
				$("#tab_"+data[1]).show(400);
				$(this).addClass("selected");
				_class = data[1];
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
		<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
			Registro personale del docente <?php echo $docente['nome']." ".$docente['cognome'] ?>
		</div>
		<div id="tabs" style="">
			<?php
			foreach ($alunni as $alunno){
				?>
				<div id="stab_<?php echo $alunno['data']['id_alunno'] ?>" class="large_tab <?php if ($std == $alunno['data']['id_alunno']) echo "selected" ?>"><?php echo $alunno['data']['cognome']." ".substr($alunno['data']['nome'], 0, 1).". (".$alunno['data']['anno_corso'].$alunno['data']['sezione'].")" ?></div>
			<?php
			}
			?>

		</div>
		<?php
		reset($alunni);
		foreach ($alunni as $k => $alunno){
			?>
			<table id="tab_<?php echo $k ?>" class="wd_95 _elem_center table_tab" style="<?php if ($alunno['data']['id_alunno'] != $std) echo "display: none" ?>">
				<tr>
					<td class="_bold" style="width: 25%; padding-left: 8px">DATA</td>
					<td class="_bold" style="width: 75%">ARGOMENTO</td>
				</tr>
				<?php
				$day = null;
				$mese = null;
				setlocale(LC_TIME, "it_IT.UTF-8");
				foreach ($alunno['attivita'] as $idx => $activity){
					list($y, $m, $d) = explode("-", $activity['data']);
					if($mese != $m){
						$str_month = ucfirst(strftime("%B", strtotime($activity['data'])));
						print("<tr><td colspan='2' style='height: 20px; vertical-align: middle; font-weight: normal; text-transform: uppercase; font-size: 13m; text-align: center; border-bottom: 1px solid #CCCCCC; background-color: rgba(211, 222, 199, 0.3); '>$str_month</td></tr>");
					}

					$giorno_str = ucfirst(strftime("%A %d", strtotime($activity['data'])));
					$print_day = ($day != $activity['data']) ? true : false;
				?>
				<tr style="border-bottom: 1px solid #CCC">
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
</body>
</html>
