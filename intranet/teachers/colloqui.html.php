<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - colloqui</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var save_data = function(){
			var url = "../../shared/save_user_config.php";
			$('#field').val("data_colloqui");
			$.ajax({
				type: "POST",
				url: url,
				data: $('#st_form').serialize(true),
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
						return;
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
			setOverlayEvent();
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
		<form method="post" name="st_form" id="st_form" class="no_border">
			<div style="width: 45%; margin: 10px auto; padding: 10px 20px 20px 20px" class="conf_frame">
				<p style="padding-bottom: 10px" class="_bold">Seleziona giorno e ora dei colloqui</p>
				<div style="margin-bottom: 10px">
					<label for="day">Giorno</label>
					<select id="day" name="day" style="margin-right: 15px;">
						<option>.</option>
						<?php
						foreach ($days as $k => $day){
						?>
						<option value="<?php echo $k ?>" <?php if(isset($data['day']) && $data['day'] == $k) echo "selected" ?>><?php echo $day ?></option>
						<?php
						}
						?>
					</select>
					<label for="day">Ora</label>
					<select id="hour" name="hour">
						<option>.</option>
						<?php
						foreach ($hours as $k => $hour){
							?>
							<option value="<?php echo $k ?>" <?php if(isset($data['hour']) && $data['hour'] == $k) echo "selected" ?>><?php echo $hour ?></option>
							<?php
						}
						?>
					</select>
					<p>
						<label for="mandatory">Prenotazione obbligatoria</label>
						<input type="checkbox" value="1" id="mandatory" name="mandatory" <?php if(isset($data['mandatory']) && $data['mandatory'] == 1) echo "checked" ?> />
					</p>
					<p>
						<label for="max">Numero max. di prenotazioni</label>
						<select id="max" name="max">
							<option value="0">.</option>
							<option value="-1" <?php if(isset($data['max']) && $data['max'] == -1) echo "selected" ?>>Nessun limite</option>
							<?php
							for($i = 1; $i < 12; $i++) {
								?>
								<option value="<?php echo $i ?>" <?php if(isset($data['max']) && $data['max'] == $i) echo "selected" ?>><?php echo $i ?></option>
								<?php
							}
							?>
						</select>
					</p>
				</div>
				<div>

				</div>
				<div class="accent_button" style="height: 20px; margin-right: 0; margin-top: 30px">
					<a href="../../shared/no_js.php" id="save_btn" style="">Registra</a>
				</div>
			</div>
			<input type="hidden" name="field" id="field" value="" />
			<input type="hidden" name="id_param" id="id_param" value="4" />
		</form>
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
</body>
</html>
