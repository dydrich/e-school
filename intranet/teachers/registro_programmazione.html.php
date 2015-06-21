<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - Registro della programmazione</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#print_menu').click(function(event) {
				event.preventDefault();
				$('#confirm_print').dialog({
					autoOpen: true,
					show: {
						effect: "appear",
						duration: 500
					},
					hide: {
						effect: "slide",
						duration: 300
					},
					modal: true,
					width: 350,
					height: 150,
					title: 'Registro della programmazione',
					open: function(event, ui){

					}
				});
			});
		});

		var create_book = function() {
			$.ajax({
				type: "POST",
				url: '../manager/planning_manager.php',
				data: {
					action: 'create',
					module: <?php echo $modulo ?>
				},
				dataType: 'json',
				error: function() {
					j_alert("error", "Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "ko") {
						j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore all'amministratore del sistema");
						return;
					}
					else {
						j_alert("alert", json.message)
						$('#dwnl > span').text("(ultima modifica il "+json.date+" alle "+json.time+ ")");
					}
				}
			});
		};

		var load_meeting = function(rid, closed) {
			if (closed) {
				//return false;
			}
			document.location.href = "riunione_programmazione.php?rid="+rid;
		};
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu_moduli.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 625px" class="rb_button">
			<a href="#" id="print_menu">
				<img src="../../images/pdf-32.png" style="padding: 4px 0 0 7px" />
			</a>
		</div>
		<div style="width: 90%; margin: 10px auto">
			<?php
			$mesi = array("", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre");
			foreach ($riunioni as $m => $mese) {
			?>
			<div class="card">
				<div class="card_title"><?php echo $mesi[$m] ?></div>
				<?php
				$idx = 0;
				foreach ($mese as $meet) {
					$due = $due_2 = null;
					$now = new DateTime();
					$dt = new DateTime($meet['data']);
					$di = $now->diff($dt);
					$closed = 1;
					if ($di->m > 0) {
						$due = "Verbale chiuso";
						$color = "#DB5355";
					}
					else {
						$dd = $dt->add(new DateInterval("P1M"));
						$open = $dd->diff($now);
						$due = "Verbale aperto";
						$due_2 = "Chiude tra ".$open->d." giorni";
						$color = "rgba(30, 67, 137, 1)";
						$closed = 0;
					}
				?>
				<a href="#" onclick="load_meeting(<?php echo $meet['id_riunione'] ?>, <?php echo $closed ?>)">
				<div class="<?php if ($idx == 0) echo "no_border" ?> card_content">
					Riunione di <?php echo strftime("%A %d", strtotime($meet['data'])) ?>, ore <?php echo substr($meet['ora_inizio'], 0, 5)." - ".substr($meet['ora_termine'], 0, 5) ?>
					<div style="width: 150px; float: right; margin-right: 10px; color: <?php echo $color ?>"><?php echo $due; if (isset($due_2)) echo "<br />$due_2" ?></div>
				</div>
				</a>
			<?php
					$idx++;
				}
			?>
			</div>
			<?php
			}
			?>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="confirm_print" style="display: none">
	<p><a href="#" onclick="create_book()">Crea o ricrea il registro</a></p>
	<p><a href="<?php if(isset($file)) echo $file; else echo "#" ?>" id="dwnl">Scarica il registro <span><?php if(isset($string_date)) echo $string_date; ?></span></a></p>
</div>
</body>
</html>
