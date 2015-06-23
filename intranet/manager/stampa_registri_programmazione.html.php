<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: registri della programmazione</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
			load_jalert();
			setOverlayEvent();
			$(".createrecord").click(function(event) {
				mod = $(this).attr("data-modulo");
				event.preventDefault();
				createPDF(mod);
			});
			$('#zip').click(function(event){
				event.preventDefault();
				document.location.href = 'planning_manager.php?action=zip';
			});
		});

		var createPDF = function(mod) {
			j_alert("working", "Creazione registro in corso");
			$.ajax({
				type: "POST",
				url: "planning_manager.php",
				data: {module: mod},
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
					else {
						j_alert("alert", json.message);
						$('#alert .alert_title i').removeClass("fa-circle-o-notch fa-spin").addClass("fa-thumbs-up");
						$("a[data-modulo='"+mod+"'].dwnlrecord > span").text(json.date+" alle "+json.time);
						$("a[data-modulo='"+mod+"'].dwnlrecord").attr("href", json.href).fadeIn(400);
					}
				}
			});
		};

		var zipFile = function(){
			$.ajax({
				type: "POST",
				url: "planning_manager.php",
				data: {action: 'zip'},
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
				}
			});
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
		<div style="position: absolute; top: 75px; margin-left: 625px" class="rb_button _center">
			<a href="#" id="zip">
				<i class="fa fa-download" style="font-size: 2em; padding: 8px 0 0 0; color: #000000"></i>
			</a>
		</div>
		<?php
		foreach ($moduli as $mod => $dati_modulo) {
			$display_link = "none";
			if ($dati_modulo['data']['data_creazione'] != "") {
				$display_link = "inline";
				$dt = $dati_modulo['data']['data_creazione'];
				$date = format_date(substr($dt, 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/");
				$time = substr($dt, 11, 5);
				$date_string = $date.' alle '.$time;
				$file = $dati_modulo['data']['file'];
			}
			$mod_string = "";
			$dir = array();
			foreach ($dati_modulo['classi'] as $cl) {
				$mod_string .= $classi[$cl] . ", ";
				$dir[] = $classi[$cl];
			}
			$f = implode("-", $dir);
			$path = "../../download/registri/".$_SESSION['__current_year__']->get_descrizione()."/scuola_primaria/programmazione/modulo".$f."/";
			$mod_string = substr($mod_string, 0, (strlen($mod_string) - 2));

		?>
		<div class="welcome">
			<p id="w_head">Modulo <?php echo $mod_string ?></p>
			<p class="w_text" style="width: 350px; margin-bottom: 5px">
				<a href="../../shared/no_js.php" data-modulo="<?php echo $mod ?>" class="createrecord">Genera registro</a>
			</p>
			<p style="margin-bottom: 0; margin-top: 0">
				<a href="<?php if(isset($file)) echo $path.$file; else echo "#" ?>" data-modulo="<?php echo $mod ?>" class="dwnlrecord" style="display: <?php echo $display_link ?>">Scarica registro (creato il <span><?php if (isset($date_string)) echo $date_string ?></span>)</a>
			</p>

		</div>
		<?php
		}
		?>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
