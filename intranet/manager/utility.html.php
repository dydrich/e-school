<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
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
		<div class="group_head">
			Area amministrazione e segreteria
		</div>
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
</body>
</html>
