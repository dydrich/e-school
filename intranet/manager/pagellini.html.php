<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>: pagellini</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var save = function(){
			var url = "gestione_pagellino.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {month: $('#month').val(), start: $('#open_at').val(), end: $('#close_at').val()},
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
					}
					else {
						j_alert("alert", json.message);
						$('table').prepend($('<tr id="tr'+json.id+'" class="bottom_decoration accent_color"><td style="width: 100px">'+json.mese+'</td><td style="width: 200px">aperto sino al '+$('#close_at').val()+'</td></tr>'))
						$('#new_report').dialog('close');
					}
				}
			});

		};

		var open_rep = function() {
			$('#new_report').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 200
				},
				hide: {
					effect: "slide",
					duration: 200
				},
				modal: true,
				width: 300,
				height: 280,
				title: 'Nuovo pagellino',
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#open_at').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('#close_at').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('.oldrep').on("click", function(event){
				id = $(this).data("id");
			});
			$('#savebutton').on('click', function(){
				save();
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
		<div class="welcome">
			<p id="w_head"><?php echo $_SESSION['__current_year__']->to_string() ?></p>
			<?php
			if (count($pagellini) == 0) {
				?>
				Nessun pagellino per l'anno in corso
				<?php
			}
			else {
			?>
			<table style="width: 300px; margin-top: 30px">
				<?php
				foreach ($pagellini as $item) {
					$state = "Stato: ";
					$today = date("Y-m-d");
					if ($item['data_chiusura'] < $today) {
						$state = "chiuso il ".format_date($item['data_chiusura'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
						$class = "normal";
					}
					else {
						$state = "aperto sino al ".format_date($item['data_chiusura'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
						$class = "accent_color";
					}
				?>
				<tr id="tr<?php echo $item['id_pagellino'] ?>" class="bottom_decoration <?php echo $class ?>">
					<td style="width: 100px"><?php echo $months[$item['mese']] ?></td>
					<td style="width: 200px"><?php echo $state ?></td>
				</tr>
				<?php
				}
				?>
			</table>
			<?php
			}
			?>
			<p style="margin-top: 30px">
				<a href="#" onclick="open_rep()" class="material_link">Nuovo pagellino</a>
			</p>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<div class="overlay" id="over1" style="display: none">
	<div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Operazione in corso</div>
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
		<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="new_report" style="width: 300px; height: 280px; display: none">
	<p style="margin: 0">Mese</p>
	<select id="month" name="month" style="width: 250px">
		<option value="0">Seleziona</option>
		<option value="11">Novembre</option>
		<option value="12">Dicembre</option>
		<option value="1">Gennaio</option>
		<option value="3">Marzo</option>
		<option value="4">Aprile</option>
		<option value="5">Mggio</option>
	</select>
	<p style="margin: 20px 0 0 0">Data apertura</p>
	<input type="text" id="open_at" style="width: 250px">
	<p style="margin: 20px 0 0 0">Data chiusura</p>
	<input type="text" id="close_at" style="width: 250px">
	<p>
		<a href="#" id="savebutton" class="material_link">Registra pagellino</a>
	</p>
</div>
</body>
</html>
