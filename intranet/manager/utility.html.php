<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
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
			$('#createreports').click(function(event){
				event.preventDefault();
				getFile("createreports");
			});
			$('#createbooks').click(function(event){
				event.preventDefault();
				getFile("createbooks");
			});
		});

		var getFile = function(action){
			$.ajax({
				type: "POST",
				url: "utilities_manager.php",
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
					else {
						if (action == "createreports"){
							$('#dw_link1').text("Scarica l'archivio");
						}
						else if (action == "createbooks"){
							$('#dw_link2').text("Scarica l'archivio");
						}
					}
				}
			});
		};

		var getAllBooks = function(){

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
		<div class="welcome">
			<p id="w_head">Schede di valutazione</p>
			<p class="w_text" style="width: 350px">
				<a href="../../shared/no_js.php" id="createreports">Crea l'archivio con tutte le schede di valutazione finali</a>
			</p>
			<p id="" class="w_text" style="width: 350px">
			<?php
			$file_zip = $_SESSION['__config__']['html_root']."/download/pagelle/pagelle_{$year_desc}.zip";
			$write = false;
			if(file_exists($file_zip)){
				$time = filemtime($file_zip);
				$write = true;
			}
			?>
				<a id="dw_link1" href='../../modules/documents/download_manager.php?doc=report_backup&area=manager&f=<?php echo basename($file_zip) ?>&sess=0&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>' style=''><?php if ($write): ?>Scarica l'archivio (ultima modifica <?php echo date("d/m/Y H:i:s", $time) ?>)<?php endif; ?></a>
			</p>
		</div>
		<div class="welcome">
			<p id="w_head">Registri docente</p>
			<p class="w_text" style="width: 350px">
				<a href="../../shared/no_js.php" id="createbooks">Scarica tutti i registri docente</a>
			</p>
			<p id="" class="w_text" style="width: 350px">
				<?php
				$file_zip = $_SESSION['__config__']['html_root']."/download/registri/registri_{$year_desc}.zip";
				$write = false;
				if(file_exists($file_zip)){
					$time = filemtime($file_zip);
					$write = true;
				}
				?>
				<a id="dw_link2" href='../../modules/documents/download_manager.php?doc=teacherbooks_archive&area=manager&f=<?php echo basename($file_zip) ?>&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>' style=''><?php if ($write): ?>Scarica l'archivio (ultima modifica <?php echo date("d/m/Y H:i:s", $time) ?>)<?php endif; ?></a>
			</p>
		</div>
		<div class="welcome">
			<p id="w_head">Varie</p>
			<p class="w_text" style="width: 350px">
				<a href="../../modules/fc/load_module.php?module=fc&area=manager" id="">Crea le nuove classi prime</a>
			</p>
		</div>
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
		<?php if ($_SESSION['__role__'] == "Dirigente scolastico"): ?>
			<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
		<?php endif; ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
