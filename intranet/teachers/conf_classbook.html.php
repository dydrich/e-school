<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var save_data = function(){
			var url = "../../shared/save_user_config.php";
			$('#field').val("riepilogo_registro");
			$.ajax({
				type: "POST",
				url: url,
				data: $('#st_form').serialize(true),
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
						sqlalert();
						console.log(json.dbg_message);
					}
					else if(json.status == "ko") {
						j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
						return;
					}
					else {
						j_alert("alert", json.message);
					}
				}
			});
		};

		$(function(){
			load_jalert();
			$('#save_btn').click(function(event){
				event.preventDefault();
				save_data();
			});
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">
			Configurazione registro di classe
		</div>
		<form method="post" name="st_form" id="st_form" class="no_border">
			<div style="width: 45%; margin: 15px auto; border: 1px solid rgba(30, 67, 137, .5); padding: 20px">
				<span>Vuoi attivare il riepilogo settimanale del Registro di classe come pagina di default?</span>
				<ul>
					<?php

					?>
					<li>
						<label for="active" style="margin-right: 10px">SI</label>
						<input type="radio" name="active" id="active" value="1" <?php if (1 == $active) echo "checked" ?> />
						<label for="active" style="margin: 0 10px 0 40px">NO</label>
						<input type="radio" name="active" id="active" value="0" <?php if (0 == $active) echo "checked" ?> />
					</li>
				</ul>
				<div style="text-align: right; width: 100%; height: 20px; margin-right: 30px; margin-top: 20px"><a href="../../shared/no_js.php" id="save_btn" style="text-transform: uppercase; text-decoration: none">Salva</a></div>
			</div>
			<input type="hidden" name="field" id="field" value="" />
			<input type="hidden" name="id_param" id="id_param" value="2" />
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
