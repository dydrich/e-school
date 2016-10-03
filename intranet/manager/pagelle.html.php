<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var timestamp;
		var tm = 0;
		var complete = false;
		var timer;

		var publish = function(q){
			quad = q;
			if(quad == 3) quad = 2;
			var url = "registra_pagelle.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {q: quad, data: $('#q'+quad+"_val").val(), ora: $('#q'+quad+"_h").val()},
				dataType: 'json',
				error: function() {
					alert("Errore di trasmissione dei dati");
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
						$('#q'+q+'_text').text("Online dal "+$('#q'+q+'_val').val()+" alle ore "+$('#q'+q+'_h').val());
						yellow_fade('upd_tr'+q);
					}
				}
			});
		};

		var create_report = function(q){
			var url = "report_manager.php";
			//loading("Creazione pagelle in corso", 10);
			background_process("Creazione pagelle in corso", 50, false);
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "create_final_report", y: <?php echo $_SESSION['__current_year__']->get_ID() ?>},
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
						loaded("Operazione conclusa");
					}
				}
			});
		};

		var do_backup = function(year, session, area){
			var url = "report_manager.php";
			loading("Creazione backup in corso", 30);

			$.ajax({
				type: "POST",
				url: url,
				data: {action: "do_backup", y: <?php echo $_SESSION['__current_year__']->get_ID() ?>, q: session},
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
						$('#tdbck_'+session).html("<a href='../../modules/documents/download_manager.php?doc=report_backup&area=manager&f="+json.zip+"&sess="+session+"&y="+year+"&area="+area+"' style=''>Scarica il backup</a>");
						//console.log(json.zip);
						loaded("Operazione conclusa");
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			<?php if((isset($pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti']) && $pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti'] == "") || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 1)): ?>
			$('#publisher1').click(function(event){
				event.preventDefault();
				publish(1);
			});
			<?php
			 else:
			 ?>
			$('.backup').click(function(event){
				//alert(this.id);
				var strs = this.id.split("_");
				y = strs[1];
				q = strs[2];
				event.preventDefault();
				do_backup(y, q, <?php echo $_SESSION['__school_order__'] ?>);
			});
			<?php endif; ?>
			<?php if((isset($pagelle[$_SESSION['__current_year__']->get_ID()][2]['disponibili_docenti']) && $pagelle[$_SESSION['__current_year__']->get_ID()][2]['disponibili_docenti'] == "") || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 2)){ ?>
			$('#publisher').click(function(event){
				event.preventDefault();
				//alert(3);
				publish(2);
			});
			<?php } ?>
			$('#gen_2').click(function(event){
				event.preventDefault();
				create_report(2);
			});
			$('#q2_val').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('#q1_val').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('#q2_h').timepicker({

			});
			$('#q1_h').timepicker({

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
		<table style="width: 550px">
			<tr id="upd_tr3">
				<td style="width: 30%; font-weight: bold">Pagella finale </td>
				<td style="width: 70%" id="q2_text">
				<?php if((isset($pagelle[$_SESSION['__current_year__']->get_ID()][2]['data_pubblicazione']) && $pagelle[$_SESSION['__current_year__']->get_ID()][2]['data_pubblicazione'] == "") || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 2)){ ?>
				<a href="#" id="sel3" style="text-decoration: none">Pubblica le pagelle il </a>
				<input type="text" style="margin-left: 8px; width: 65px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q2_val" id="q2_val" />
				<label for="f_h" style="margin-left: 15px">alle ore </label>
				<input type="text" style="margin-left: 8px; width: 35px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q2_h" id="q2_h" />
				<a href="../../shared/no_js.php" id="publisher" style="margin-left: 10px">Registra</a>
				<?php } 
				else{
					if(isset($pagelle[$_SESSION['__current_year__']->get_ID()][2]['data_pubblicazione'])) {
						$d = format_date($pagelle[$_SESSION['__current_year__']->get_ID()][2]['data_pubblicazione'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
						$h = substr($pagelle[$_SESSION['__current_year__']->get_ID()][2]['ora_pubblicazione'], 0, 5);
					}
					else {
						$d = $h = "";
					}
				?>
				Online dal <?php echo $d ?> alle ore <?php echo $h ?> (<a href="pagelle.php?force_modification=2" id="mod">modifica</a>)
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 40px">
					
					<?php if(isset($pagelle[$_SESSION['__current_year__']->get_ID()][2]['data_pubblicazione']) && $pagelle[$_SESSION['__current_year__']->get_ID()][2]['disponibili_docenti'] != "" && $pagelle[$_SESSION['__current_year__']->get_ID()][2]['disponibili_docenti'] <= date("Y-m-d")){ ?>
					<a href="../../shared/no_js.php" id="gen_2">Genera o rigenera pagelle</a><br />
					<a href="cerca_pagella.php?y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&q=2">Cerca una pagella</a><br />
					<a href="" class="backup" id="backup_<?php echo $_SESSION['__current_year__']->get_ID() ?>_2">Crea il backup pagelle</a><br />
					<?php
					$folder = "scuola-secondaria";
					if ($_SESSION['__school_order__'] == 2){
						$folder = "scuola-primaria";
					}
					$year_desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID());
					$file_zip = $folder."-".$year_desc."-2Q.zip";

					if(file_exists($_SESSION['__config__']['html_root']."/download/pagelle/{$year_desc}/{$file_zip}")){
						$time = filemtime($_SESSION['__config__']['html_root']."/download/pagelle/{$year_desc}/{$file_zip}");
					?>
						<a href='../../modules/documents/download_manager.php?doc=report_backup&area=manager&f=<?php echo $file_zip ?>&sess=2&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&area=<?php echo $_SESSION['__school_order__'] ?>' style=''>Scarica il backup (ultima modifica <?php echo date("d/m/Y H:i:s", $time) ?>)</a>
					<?php
						}
					}
					?>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="height: 20px"></td>
			</tr>
			<tr>
				<td colspan="2" style="font-weight: bold">Schede di valutazione quadrimestrali</td>
			</tr>
			<tr id="upd_tr1">
				<td style="width: 30%">Primo Quadrimestre</td>
				<td style="width: 70%" id="q1_text">
				<?php if((isset($pagelle[$_SESSION['__current_year__']->get_ID()][1]['data_pubblicazione']) && $pagelle[$_SESSION['__current_year__']->get_ID()][1]['data_pubblicazione'] == "") || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 1)){ ?>
				<a href="#" id="sel" style="text-decoration: none">Pubblica le pagelle il </a>
				<input type="text" style="margin-left: 8px; width: 65px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q1_val" id="q1_val" />
				<label for="q1_h" style="margin-left: 15px">alle ore </label>
				<input type="text" style="margin-left: 8px; width: 35px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q1_h" id="q1_h" />
				<a href="#" id="publisher1" style="margin-left: 10px">Registra</a>
				<?php
				}
				else{
					if(isset($pagelle[$_SESSION['__current_year__']->get_ID()][1]['data_pubblicazione'])) {
						$d = format_date($pagelle[$_SESSION['__current_year__']->get_ID()][1]['data_pubblicazione'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
						$h = substr($pagelle[$_SESSION['__current_year__']->get_ID()][1]['ora_pubblicazione'], 0, 5);
					}
					else {
						$d = $h = "";
					}
				?>
				Online dal <?php echo $d ?> alle ore <?php echo $h ?> (<a href="pagelle.php?force_modification=1" id="mod">modifica</a>)
				<?php } ?>
				</td>
			</tr>
			<?php if(isset($pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti']) && $pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti'] != "" && $pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti'] <= date("Y-m-d")): ?>
			<tr>
				<td colspan="2" style="padding-left: 40px">
					<a href="cerca_pagella.php?y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&q=1">Cerca una scheda</a><br />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 40px">
					<a href="" class="backup" id="backup_<?php echo $_SESSION['__current_year__']->get_ID() ?>_1">Crea il backup pagelle</a>
				</td>
			</tr>
			<tr>
				<td id="tdbck_1" colspan="2" style="padding-left: 40px">
					<?php
					$folder = "scuola_secondaria";
					if ($_SESSION['__school_order__'] == 2){
						$folder = "scuola_primaria";
					}
					$year_desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID());
					$file_zip = $folder."-".$year_desc."-1Q.zip";

					if(file_exists($_SESSION['__config__']['html_root']."/tmp/{$year_desc}/1/{$folder}/{$file_zip}")){
						$time = filemtime($_SESSION['__config__']['html_root']."/tmp/{$year_desc}/1/{$folder}/{$file_zip}");
					?>
						<a href='../../modules/documents/download_manager.php?doc=report_backup&area=manager&f=<?php echo $file_zip ?>&sess=1&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&area=<?php echo $_SESSION['__school_order__'] ?>' style=''>Scarica il backup (ultima modifica <?php echo date("d/m/Y H:i:s", $time) ?>)</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<td colspan="2" style="height: 20px"></td>
			</tr>
		</table>
	</div>
	<div class="welcome">
		<p id="w_head">Anni precedenti</p>
		<?php if(count($pagelle) < 2){ ?>
		<p class="w_text">Nessuna pagella presente</p>
		<?php 
		} 
		else{ 
			foreach($pagelle as $k => $pagella){
				if($k != $_SESSION['__current_year__']->get_ID()){
					$desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = {$k}");
		?>
			<p><a href="cerca_pagella.php?y=<?php echo $k ?>&q=2" class="search">Anno scolastico <?php echo $desc ?></a></p>
		<?php
				}
			}
		} 
		?>
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
</body>
</html>
