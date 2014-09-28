<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>::area docenti</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
			load_jalert();
		});

		var select_theme = function(theme) {
			$.ajax({
				type: "POST",
				url: '../../shared/config_manager.php',
				data: {action: 'select_theme', value: theme, conf: 1},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", "Caricamento tema");
						setTimeout(
							function() {
								document.location.href = "themes.php";
							},
							2000
						);
					}
				}
			});
		};
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "profile_working.php" ?>
	</div>
	<div id="left_col">
		<div style="width: 90%; margin: auto; padding-top: 25px">
			<div class="card">
				<div class="card_title">Tema attuale: <?php echo $default_theme['name'] ?></div>
				<div class="card_varcontent"><img src="../../images/themes_thumbnails/<?php echo $default_theme['img'] ?>" /></div>
			</div>
			<?php
			foreach ($themes as $k => $theme) {
			?>
			<div class="card">
				<div class="card_title"><?php echo $theme['name'] ?> (<a href="#" onclick="select_theme(<?php echo $k ?>)">seleziona tema</a>)</div>
				<div class="card_varcontent"><img src="../../images/themes_thumbnails/<?php echo $theme['img'] ?>" /></div>
			</div>
			<?php
			}
			?>
		</div>
	</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
