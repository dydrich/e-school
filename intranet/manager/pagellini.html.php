<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>: pagellini</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var repID = 0;
		var month = 0;
		var save = function(action){
			var url = "gestione_pagellino.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {month: $('#month').val(), start: $('#open_at').val(), end: $('#close_at').val(), id: repID, action: action},
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
						if (repID == 0) {
							$('table').prepend($('<tr id="tr' + json.id + '" class="bottom_decoration accent_color"><td style="width: 100px">' + json.mese + '</td><td style="width: 200px">disponibile dal ' + $('#open_at').val() + '</td><td style="25px"></td><td style="25px" class="_right"><a href="#" class="del_rep" data-id="' + json.id + '"><i class="fa fa-trash"></i></a></td></tr>'));
							$('.del_rep').on('click', function(event) {
								$id = $(this).data("id");
								repID = $id;
								save('delete');
							});
						}
						else {
							if (action != 'delete') {
								$('#tr'+json.id+' td:nth-child(2)').text(json.state);
							}
							else {
								$('#tr'+json.id).hide();
							}
						}
						if (action != 'delete') {
							window.setTimeout(function () {
								$('#new_report').dialog('close');
							}, 2000);
						}
					}
				}
			});

		};

		var show_reports = function() {
			$('#rep_div').dialog({
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
				width: 250,
				height: 380,
				title: 'Elenco schede di segnalazione',
				open: function(event, ui){
					$('#reports_container').html('');
					$('#cls').val(0);
				},
				close: function(event) {
					$('#overlay').hide();
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
				save('');
			});
			$('.rep').on('click', function(event) {
				$id = $(this).data("id");
				repID = $id;
				$month = $(this).data('month');
				$opens = $(this).data("open");
				$closes = $(this).data("end");
				$('#month').val($month);
				$('#open_at').val($opens);
				$('#close_at').val($closes);
				open_rep();
			});
			$('.del_rep').on('click', function(event) {
				$id = $(this).data("id");
				repID = $id;
				save('delete');
			});
			$('.search_rep').on('click', function(event) {
				$id = $(this).data("id");
				repID = $id;
				month = $(this).data('month');
				show_reports();
			});
			$('#cls').on('change', function(event) {
				$('#reports_container').html('');
				$cls = $(this).val();
				var url = "../../shared/get_monthly_report.php";

				$.ajax({
					type: "POST",
					url: url,
					data: {action: 'search', id: repID, cls: $cls},
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
						else if (json.status == 'no_st') {
							$('<p class="_center _bold" style="margin-top: 40px">'+json.message+'</p>').appendTo($('#reports_container'));
						}
						else {
							students = json.students;
							for(t in students) {
								if (students.hasOwnProperty(t)) {
									var student = students[t];
									var st = student.id_alunno;
									var link = "../../shared/get_monthly_report.php?st=" + st + "&m=" + month;
									$('<p style="line-height: 25px; margin: 0"><a href="' + link + '" class="normal" style="text-decoration: none">' + student.cognome + ' ' + student.nome + '</a></p>').appendTo($('#reports_container'));
								}
							}
						}
					}
				});
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
			<table style="width: 350px; margin-top: 30px">
				<?php
				foreach ($pagellini as $item) {
					$state = "Stato: ";
					$today = date("Y-m-d");
					if ($item['data_chiusura'] < $today) {
						$state = "chiuso il ".format_date($item['data_chiusura'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
						$class = "normal";
					}
					else {
						if ($item['data_apertura'] <= $today) {
							$state = "aperto sino al ".format_date($item['data_chiusura'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
						}
						else {
							$state = "disponibile dal ".format_date($item['data_apertura'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
						}
						$class = "accent_color";
					}
				?>

				<tr id="tr<?php echo $item['id_pagellino'] ?>" class="bottom_decoration <?php echo $class ?>" style="height: 30px">
					<td style="width: 100px">
						<a href="#" class="rep" data-id="<?php echo $item['id_pagellino'] ?>" data-month="<?php echo $item['mese'] ?>" data-open="<?php echo format_date($item['data_apertura'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" data-end="<?php echo format_date($item['data_chiusura'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>">
							<?php echo $months[$item['mese']] ?>
						</a>
					</td>
					<td style="width: 200px"><?php echo $state ?></td>
					<td style="width: 25px" class="_right">
					<?php if ($item['data_chiusura'] < $today) : ?>
						<a href="#" class="search_rep" data-id="<?php echo $item['id_pagellino'] ?>" data-month="<?php echo $item['mese'] ?>">
							<i class="fa fa-search"></i>
						</a>
					<?php endif; ?>
					</td>
					<td style="width: 25px" class="_right">
						<a href="#" class="del_rep" data-id="<?php echo $item['id_pagellino'] ?>">
							<i class="fa fa-trash"></i>
						</a>
					</td>
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
<div id="rep_div" style="display: none; width: 250px; min-height: 250px">
	<div id="change_class" style="width: 100%; height: 25px">
		<select id="cls" name="cls" style="width: 95%">
			<option value="0">.</option>
			<?php
			while($row = $res_cls->fetch_assoc()) {
			?>
				<option value="<?php echo $row['id_classe'] ?>"><?php echo $row['anno_corso'], $row['sezione'] ?></option>
			<?php
			}
			?>
		</select>
	</div>
	<div id="reports_container">

	</div>
</div>
</body>
</html>
