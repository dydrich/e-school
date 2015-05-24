<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Attivazione del software</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		var cdc_created = <?php print $exist_cdc ?>;
		var reg_created = <?php print $exist_reg ?>;
		var schedule_created = <?php print $exist_sch ?>;

	var tm = 0;
	var complete = false;
	var timer;

		function crea_cdc(){
			if(cdc_created){
				if(!confirm("I dati relativi ai consigli di classe sono già presenti in archivio. Vuoi modificarli?")) {
					return false;
				}
				else {
					document.location.href = "cdc_state.php";
					return false;
				}
			}
			var url = "crea_cdc.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						alert("Operazione conclusa con successo");
					}
				}
			});
		}

		function crea_orario(){
			action = "insert";
			if(schedule_created){
				if(!confirm("I dati relativi all'orario sono già presenti in archivio. Vuoi cancellarli e ricrearli? ATTENZIONE: cliccando su OK tutte le modifiche apportate all'orario verranno perse.")) {
					return false;
				}
				else {
					action = "reinsert";
				}
			}
			var url = "popola_tabella_orario.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: action},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						show_error(json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "ko"){
						show_error(json.message);
					}
					else {
						alert("Operazione conclusa con successo");
					}
				}
			});
		}

		var crea_registro = function(){
			if(reg_created){
				if(!confirm("I dati relativi al registro di classe sono già presenti in archivio. Vuoi modificarli?")) {
					return false;
				}
				else {
					document.location.href = "classbook_state.php";
					return false;
				}
			}
			var url = "classbook_manager.php";
			background_process("Operazione in corso", 20, true);
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "insert"},
				dataType: 'json',
				error: function() {
					clearTimeout(bckg_timer);
					$('#background_msg').text("Errore di trasmissione dei dati");
					setTimeout(function() {
						$('#background_msg').dialog("close");
					}, 2000);
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
						console.log(json.dbg_message);
						clearTimeout(bckg_timer);
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);

					}
					else if(json.status == "ko"){
						console.log(json.dbg_message);
						clearTimeout(bckg_timer);
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
						return;
					}
					else {
						clearTimeout(bckg_timer);
						$('#background_msg').text("Operazione conclusa");
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
						reg_created = 999;
					}
				}
			});
		};


	var pop_scrutini = function(){
		if(scr_created1 > 0){
			_alert("Operazione gia` effettuata");
			return false;
		}
		else{
			var url = "popola_tabella_scrutini.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {quadrimestre: 1, action: "reinsert"},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					complete = true;
					clearTimeout(timer);
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						console.log(json.dbg_message);
						$('#wait_label').text(json.message);
						setTimeout("$('#wait_label').hide(2000)", 2000);
						setTimeout("$('#overlay').hide()", 3800);

					}
					else if(json.status == "ko"){
						$('#wait_label').text(json.message);
						setTimeout("$('#wait_label').hide(2000)", 2000);
						setTimeout("$('#overlay').hide()", 3800);
						return;
					}
					else {
						scr_created1 = 999;
						$.ajax({
							type: "POST",
							url: url,
							data: {quadrimestre: 2, action: "reinsert"},
							dataType: 'json',
							error: function() {
								j_alert("error", "Errore di trasmissione dei dati");
							},
							succes: function() {

							},
							complete: function(data){
								complete = true;
								clearTimeout(timer);
								r = data.responseText;
								if(r == "null"){
									return false;
								}
								var json = $.parseJSON(r);
								if (json.status == "kosql"){
									console.log(json.dbg_message);
									$('#wait_label').text(json.message);
									setTimeout("$('#wait_label').hide(2000)", 2000);
									setTimeout("$('#overlay').hide()", 3800);

								}
								else if(json.status == "ko"){
									$('#wait_label').text(json.message);
									setTimeout("$('#wait_label').hide(2000)", 2000);
									setTimeout("$('#overlay').hide()", 3800);
									return;
								}
								else {
									scr_created2 = 999;
								}
							}
						});
					}
				}
			});
		}
	};

	var close_and_go = function(){
		var url = "../shared/update_env.php";

		$.ajax({
			type: "POST",
			url: url,
			data: {field: 'installazione_completata', value: '1'},
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
				else if(json.status == "ko"){
					j_alert("error", json.message);
				}
				else {
					document.location.href = "index.php";
				}
			}
		});
	};

	$(function(){
		load_jalert();
		setOverlayEvent();
	})

	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div style="width: 90%; margin: 20px auto 0 auto">
			<p class="admin_title_row">Procedura guidata: prima installazione</p>
			<?php if($step < 2){ ?>
			<p>Benvenuto, admin! Se ti trovi in questa pagina, significa che l'installazione del software &egrave; terminata correttamente.</p>
			<p>Prima per&ograve; che i tuoi utenti possano accedervi e utilizzarlo, sono necessarie alcune operazione, che solo gli utenti con i permessi
				di amministrazione possono compiere. <br />Tutte queste operazioni sono disponibili dal menu di amministrazione (che puoi raggiungere in qualsiasi
				momento utilizzando il link in fondo a questa pagina).<br/>
				<?php } ?>
				<?php include "wiz_first_install_inc{$step}.php" ?>
			<div style="width: 100%; text-align: right; margin-top: 30px">
				<?php if($step > 1){ ?>
					<a href="wiz_first_install.php?step=<?php echo ($step - 1) ?>" style="float: left">Torna indietro</a>
				<?php } ?>
				<?php if($step != 5){ ?>
					<a href="wiz_first_install.php?step=<?php echo ($step + 1) ?>" class="nav_link_first material_link">Prosegui</a>
					<a href="index.php" class="nav_link_last material_link">Vai al menu</a>
				<?php } else { ?>
					<a href="../shared/no_js.php" id="done_lnk" class="nav_link_last material_link">Termina</a>
				<?php } ?>

			</div>
		</div>


	<div class="overlay" id="over1" style="display: none">
		<div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Caricamento dati in corso</div>
	</div>

	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php" class=""><i class="fa fa-home _right material_label" style="font-size: 1.15em; margin-right: 10px; position: relative; top: 2%; width: 20px"></i>Home</a></div>
		<div class="drawer_link"><a href="index.php"><i class="fa fa-keyboard-o" style="font-size: 1.15em; margin-right: 10px; position: relative; top: 2%; width: 20px"></i>Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><i class="fa fa-university" style="font-size: 1.15em; margin-right: 10px; position: relative; top: 2%; width: 20px"></i>Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><i class="fa fa-desktop" style="font-size: 1.15em; margin-right: 10px; position: relative; top: 2%; width: 20px"></i>Logout</a></div>
</div>
</body>
</html>
