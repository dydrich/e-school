<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Dettaglio evento</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javaScript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#save_button').click(function(event){
				event.preventDefault();
				save();
			});
			$('#tipo').focus();
		});

		var save = function(){
			if ($('#tipo').val() == "" && $('#descrizione').val() == "") {
				j_alert("error", "I campi Tipo e Descrizione sono obbligatori");
				$('#tipo').focus();
				return;
			}

			var url = "events_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: $('#event_form').serialize(true),
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
						return;
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
						return;
					}
					else {
						j_alert("alert", "Operazione conclusa");
						setTimeout(function(){
							document.location.href = "events_list.php";
						}, 2000);
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
		<?php include "stat_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 225px; margin-bottom: -5px" class="rb_button">
			<a href="events_list.php">
				<img src="../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<form action="events_manager.php" method="post" id="event_form" class="no_border" style="width: 90%">
			<div style="text-align: left; width: 100%; margin: auto; ">
				<fieldset style="margin-right: auto; margin-left: auto; margin-bottom: 20px; padding-bottom: 20px; width: 95%; padding-top: 10px">
					<legend>Evento</legend>
					<table style="margin: auto; width: 95%">
						<tr>
							<td style="width: 30%"><label for="tipo" class="popup_title">Tipo *</label></td>
							<td style="width: 70%">
								<input class="form_input" type="text" name="tipo" id="tipo" style="width: 100%" value="<?php if(isset($event)) echo $event['tipo'] ?>" />
							</td>
						</tr>
						<tr>
							<td style="width: 30%"><label for="descrizione" class="popup_title">Descrizione *</label></td>
							<td style="width: 70%">
								<textarea id="descrizione" name="descrizione" style="width: 100%; height: 40px"><?php if(isset($event)) echo $event['descrizione'] ?></textarea>
							</td>
						</tr>
						<tr>
							<td style="width: 30%"><label for="numeric1" class="popup_title">Campo numerico 1</label></td>
							<td style="width: 70%">
								<input class="form_input" type="text" id="numeric1" name="numeric1" style="width: 100%" value="<?php if(isset($event)) echo $event['numeric1'] ?>" />
							</td>
						</tr>
						<tr>
							<td style="width: 30%"><label for="numeric1" class="popup_title">Campo numerico 2</label></td>
							<td style="width: 70%">
								<input class="form_input" type="text" id="numeric2" name="numeric2" style="width: 100%" value="<?php if(isset($event)) echo $event['numeric2'] ?>" />
							</td>
						</tr>
						<tr>
							<td style="width: 30%"><label for="numeric1" class="popup_title">Campo testo 1</label></td>
							<td style="width: 70%">
								<input class="form_input" type="text" id="text1" name="text1" style="width: 100%" value="<?php if(isset($event)) echo $event['text1'] ?>" />
							</td>
						</tr>
						<tr>
							<td style="width: 30%"><label for="numeric1" class="popup_title">Campo testo 1</label></td>
							<td style="width: 70%">
								<input class="form_input" type="text" id="text2" name="text2" style="width: 100%" value="<?php if(isset($event)) echo $event['text2'] ?>" />
							</td>
						</tr>
						<tr>
							<td style="width: 30%"><label for="numeric1" class="popup_title">Campo float 1</label></td>
							<td style="width: 70%">
								<input class="form_input" type="text" id="float1" name="float1" style="width: 100%" value="<?php if(isset($event)) echo $event['float1'] ?>" />
							</td>
						</tr>
					</table>
					<div style="width: 98%; text-align: right; margin-top: 20px">
						<a href="#" id="save_button" class="material_link">Registra</a>
					</div>
				</fieldset>
			</div>
			<input type="hidden" name="action" id="action" value="<?php if ($id == 0) echo "insert"; else echo "update" ?>" />
			<input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
